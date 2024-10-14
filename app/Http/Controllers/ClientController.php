<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;

class ClientController extends Controller
{
    /**
     * Display the client view
     */
    public function index()
    {
        // Instead of returning a JSON response, return the Blade view
        return view('clients');
    }

    /**
     * Get all clients data paginated
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getClients()
    {
        // Retrieve paginated clients
        $clients = Client::paginate(10);

        // Ensure each client has the full avatar URL
        $clients->getCollection()->transform(function ($client) {
            $client->avatar_url = asset('storage/' . $client->avatar);
            return $client;
        });

        // Return the paginated clients with the avatar URL
        return response()->json($clients);
    }

    /**
     * Get all clients without pagination
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllClients()
    {
        return response()->json(Client::all());
    }

    /**
     * Store a newly created client in storage.
     *
     * @param \App\Http\Requests\StoreClientRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreClientRequest $request)
    {
        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        // Create the client with the validated data from the StoreClientRequest
        $client = Client::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'avatar' => $avatarPath ?? null,
        ]);

        return response()->json($client, 201);
    }

    /**
     * Display a specified client.
     *
     * sos: frontend doesn't have an option for showing only one specific client
     *
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //Find the transaction that needs to be showed
        $client = Client::findOrFail($id);

        // Retrieve the client with the related transactions
        return response()->json($client->load('transactions'));

    }

    /**
     * Update the specified client in storage.
     *
     * @param \App\Http\Requests\UpdateClientRequest $request
     * @param                                        $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateClientRequest $request, $id)
    {
        // Find the client by ID
        $client = Client::findOrFail($id);

        // Check if a new avatar is uploaded
        if ($request->hasFile('avatar')) {
            // If the current avatar is not the default 'avatar.png', delete the old avatar
            if ($client->avatar !== 'avatars/avatar.png') {
                Storage::disk('public')->delete($client->avatar);
            }

            // Store the new avatar and update the avatar path
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $client->avatar = $avatarPath;
        }

        // Update client with validated data
        $client->update($request->only('first_name', 'last_name', 'email'));


        return response()->json(['message' => 'Client updated successfully', 'client' => $client]);
    }

    /**
     * Remove the specified client from storage.
     *
     * @param Client $client
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        // Find the client by ID
        $client = Client::findOrFail($id);

        // Check if the avatar is not named 'avatar.png' before deleting it
        //clients created from the seeder will use the default avatar and we dont want to delete it.
        if ($client->avatar !== 'avatars/avatar.png') {
            // Delete the avatar file from storage
            Storage::disk('public')->delete($client->avatar);
        }

        // Delete the client
        $client->delete();

        return response()->json(['message' => 'Client deleted successfully'], 200);

    }
}
