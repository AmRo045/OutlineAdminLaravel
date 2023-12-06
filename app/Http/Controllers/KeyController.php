<?php

namespace App\Http\Controllers;

use App\Services\OutlineVPN\AccessKey;
use Illuminate\Http\Request;

class KeyController extends Controller
{
    public function index()
    {
        $serverInfoRequest = api()->server();

        if ($serverInfoRequest->succeed) {
            $server = $serverInfoRequest->result;

            $keysRequest = api()->keys();

            if ($keysRequest->succeed) {
                $keys = collect($keysRequest->result->accessKeys)->map(fn ($key) => AccessKey::fromObject($key));

                return view('servers.keys.index', compact('server', 'keys'));
            }

            $keysRequest->throw();
        }

        $serverInfoRequest->throw();
    }

    public function create()
    {
        return view('servers.keys.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:64'
        ]);

        $newKeyRequest = api()->createKey();

        if (! $newKeyRequest->succeed) {
            $newKeyRequest->throw();
        }


        // TODO: Store the key in the database
        $outlineAccessKey = AccessKey::fromObject($newKeyRequest->result);

        $renameRequest = api()->renameKey($outlineAccessKey->id, $request->name);
        if (!$renameRequest->succeed) {
            $renameRequest->throw();
        }

        return redirect()->route('keys.index');
    }

    public function edit()
    {
        return view('servers.keys.edit');
    }

    public function update(Request $request, int $key)
    {
        $request->validate([
            'name' => 'required|string|max:64'
        ]);

        $renameRequest = api()->renameKey($key, $request->name);
        if (!$renameRequest->succeed) {
            $renameRequest->throw();
        }

        return redirect()->route('keys.index');
    }

    public function destroy(int $key)
    {
        $deleteKeyRequest = api()->deleteKey($key);

        if ($deleteKeyRequest->succeed) {
            // TODO: Delete key from database
            return redirect()->route('keys.index');
        }

        $deleteKeyRequest->throw();
    }
}
