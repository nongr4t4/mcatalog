<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Основна статистика
        $stats = [
            'orders_count' => Order::count(),
            'clients_count' => User::where('role', 'user')->count(),
            'revenue' => Order::where('status', 'completed')->sum('total_amount'),
            'products_count' => Product::count(),
            'recent_orders' => Order::with('user')->latest()->take(5)->get()
        ];

        // 2. Підготовка даних для графіку (останні 30 днів)
        $endDate = Carbon::now();
        $startDate = Carbon::now()->subDays(29);

        // Отримуємо суму продажів по днях
        $ordersData = Order::where('status', 'completed')
            ->whereBetween('created_at', [$startDate->startOfDay(), $endDate->endOfDay()])
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as total')
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $chartLabels = [];
        $chartValues = [];

        // Заповнюємо пропущені дні нулями
        for ($i = 0; $i < 30; $i++) {
            $date = $startDate->copy()->addDays($i)->format('Y-m-d');
            $chartLabels[] = $startDate->copy()->addDays($i)->format('d.m'); // Формат для підпису (день.місяць)
            $chartValues[] = $ordersData[$date] ?? 0;
        }

        return view('admin.dashboard', compact('stats', 'chartLabels', 'chartValues'));
    }
}