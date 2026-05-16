<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\V1\Address\StoreAddressRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddressController
{
    //

    public function store(StoreAddressRequest $request)
    {
        $user = Auth::user();
        $data = $request->validated();

        // If setting as default, unset others
        if (!empty($data['is_default']) && $data['is_default']) {
            $user->addresses()->update(['is_default' => false]);
        }

        $address = $user->addresses()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Address created successfully',
            'data' => $address
        ], 201);
    }
    //       $user = Auth::user();
    //     $data = $request->validated();

    //     DB::transaction(function () use ($user, $data) {

    //     $data['is_default'] = filter_var(
    //         $data['is_default'] ?? false,
    //         FILTER_VALIDATE_BOOLEAN
    //     );

    //     if ($data['is_default']) {
    //         $user->addresses()
    //             ->where('is_default', true)
    //             ->update(['is_default' => false]);
    //     }

    //     return $user->addresses()->create($data);
    // });
    // }

    public function index()
    {
        $addresses = Auth::user()->addresses()->latest()->get();

        return response()->json([
            'success' => true,
            'data' => $addresses
        ]);
    }

    public function setDefault(Address $address)
    {
        $user = Auth::user();

        if ($address->user_id !== $user->id) {
            // abort(403, 'Unauthorized');
             return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
                'errors' =>  ['cart' => 'Unauthorized']
            ], 403);
        }

        $user->addresses()->update(['is_default' => false]);

        $address->update(['is_default' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Default address updated'
        ]);
    }

    public function update(StoreAddressRequest $request, $id)
    {
        $address = Address::findOrFail($id);

        $address->update($request->validated());

        return response()->json([
            'success' => true,
            'data' => $address
        ]);
    }
    public function destroy($id)
    {
        $address = Address::findOrFail($id);
        $address->delete();

        return response()->json([
            'message' => 'Address deleted'
        ]);
    }
}
