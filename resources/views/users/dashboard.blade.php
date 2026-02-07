@extends('layouts.app')
@section('title', 'ダッシュボード')
@section('description', 'ダッシュボードです。')

@section('content')
    @isset($dashboard)
        <div class="dashboard-wrapper">
            <div class="dashboard-box">
                <h2 class="dashboard-title">📊 ダッシュボード</h2>

                <ul class="dashboard-list">
                    <li class="dashboard-item">
                        <span class="dashboard-label">今日のPV</span>
                        <span class="dashboard-count">
                            {{ number_format($dashboard['today_views']) }}
                        </span>
                    </li>

                    <li class="dashboard-item">
                        <span class="dashboard-label">今日のいいね</span>
                        <span class="dashboard-count">
                            {{ number_format($dashboard['today_likes']) }}
                        </span>
                    </li>

                    <li class="dashboard-item">
                        <span class="dashboard-label">今日のリアクション</span>
                        <span class="dashboard-count">
                            {{ number_format($dashboard['today_reactions']) }}
                        </span>
                    </li>

                    <li class="dashboard-item">
                        <span class="dashboard-label">累計PV</span>
                        <span class="dashboard-count">
                            {{ number_format($dashboard['total_views']) }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>
    @else
        <div class="dashboard-wrapper">
            <div class="dashboard-box">
                <p class="text-muted mb-0">
                    ダッシュボードを表示するデータがありません。
                </p>
            </div>
        </div>
    @endisset
@endsection
