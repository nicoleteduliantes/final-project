<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OsaController extends Controller
{
    public function registerOrg($data)
    {
        return Organization::create([
            'org_name'    => $data['org_name'],
            'category'    => $data['category'],
            'description' => $data['description'],
            'password'    => Hash::make($data['password']),
        ]);
    }
}
