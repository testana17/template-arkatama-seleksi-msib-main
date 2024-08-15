<?php

namespace App\Http\Controllers;

use App\DataTables\Asesor\FilePanduan\PanduanAsesorDataTable;
use App\Http\Controllers\Admin\AdminController;
use App\Models\User;

class HomeController extends Controller
{
    protected $modules = ['dashboard'];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        parent::__construct();
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $currentUser = auth()->user();

        switch ($currentUser->roles[0]->name) {
            case 'admin':
                $admin = new AdminController;

                return $admin->admin();

            default:
                $admin = new AdminController;

                return $admin->admin();
        }
    }

    public function asesor()
    {
        $user = User::where('id', auth()->id())->first();
        $dataTable = new PanduanAsesorDataTable;

        return $dataTable->render('pages.admin.asesor.dashboard.index', compact('user'));
    }
}
