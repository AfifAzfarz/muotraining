<?php

namespace App\Http\Controllers\Integration;

use App\Http\Controllers\Controller;
use App\Models\Negeri;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class EndpointController extends Controller
{
    public function post(Request $request)
    {
        try {
            $posts = Post::get();

            return response()->json([
                'success' => true,
                'message' => 'Request successful',
                'data'    => $posts,
            ], Response::HTTP_OK); // 200

        } catch (QueryException $e) {
            // Database related errors
            return response()->json([
                'success' => false,
                'message' => 'Database error',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error'   => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        }
    }

    public function fetchAndStore()
    {
        // 1. Call the external API
        $response = Http::timeout(30)
            ->withHeaders([
                // 'Authorization' => 'Bearer ' . config('services.external_api.token'),
                'Accept'        => 'application/json',
            ])
            ->get('https://api.muo.gov.my/api/ref/negeri');

        // 2. Handle HTTP-level errors
        if ($response->failed()) {
            Log::error('External API request failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return response()->json([
                'message' => 'Failed to reach external API.',
                'status'  => $response->status(),
            ], 502);
        }

        // 3. Decode and validate the JSON payload
        $data = $response->json('data'); // e.g. { "data": [...] }

        if (empty($data) || !is_array($data)) {
            return response()->json(['message' => 'No data returned from API.'], 422);
        }

        // 4. Store records inside a transaction so it's all-or-nothing
        DB::beginTransaction();

        try {
            $inserted = 0;

            foreach ($data as $item) {
                Negeri::updateOrCreate(
                    ['attribute_id' => $item['id']], // to prevent duplication
                    [
                        // 'attribute_id' => $item['id'],
                        'nama'       => $item['nama']       ?? null,
                        'kod_negeri'      => $item['kod']      ?? null,
                        'status'     => $item['status']     ?? 'Active',
                        'synced_at'  => now(),
                    ]
                );

                $inserted++;
            }

            DB::commit();

            return response()->json([
                'message'  => 'Data synced successfully.',
                'inserted' => $inserted,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Failed to store API data', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => 'Failed to store data.',
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
