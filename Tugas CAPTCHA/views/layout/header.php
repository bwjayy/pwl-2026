<?php
$currentUser = Auth::user();
$currentNav = $activeNav ?? 'accounts';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem IAM TIK PNJ - Manajemen Akun dan Hak Akses</title>
    <style>
        :root {
            --bg-base: #F8FAFC;
            --bg-surface: #FFFFFF;
            --border-color: #E2E8F0;
            --border-focus: #3B82F6;
            --text-main: #0F172A;
            --text-muted: #475569;
            --brand-primary: #1E3A8A;
            --brand-primary-hover: #172554;
            --accent-blue: #0284C7;
            --status-active-bg: #DCFCE7;
            --status-active-text: #15803D;
            --status-inactive-bg: #FEE2E2;
            --status-inactive-text: #B91C1C;
            --btn-danger: #B91C1C;
            --btn-danger-hover: #991B1B;
            --font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            --font-mono: ui-monospace, "SFMono-Regular", Menlo, Monaco, Consolas, monospace;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: var(--font-family);
            background-color: var(--bg-base);
            color: var(--text-main);
            line-height: 1.5;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Skip Link for Accessibility */
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: var(--brand-primary);
            color: #ffffff;
            padding: 8px 16px;
            z-index: 1000;
            text-decoration: none;
            font-weight: 600;
        }
        .skip-link:focus {
            top: 0;
        }

        /* Layout Structure */
        .app-shell {
            display: flex;
            min-height: 100vh;
            width: 100%;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 260px;
            background-color: #0F172A;
            color: #F8FAFC;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
            border-right: 1px solid #1E293B;
        }

        .sidebar-brand {
            padding: 24px 20px;
            border-bottom: 1px solid #1E293B;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-badge {
            background-color: #1E3A8A;
            color: #93C5FD;
            width: 36px;
            height: 36px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            border: 1px solid #2563EB;
        }

        .brand-info h1 {
            font-size: 0.95rem;
            font-weight: 700;
            color: #FFFFFF;
            letter-spacing: -0.2px;
        }

        .brand-info p {
            font-size: 0.75rem;
            color: #94A3B8;
        }

        .sidebar-nav {
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex-grow: 1;
        }

        .nav-label {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748B;
            padding: 8px 12px 4px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: #CBD5E1;
            text-decoration: none;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 500;
            transition: background-color 150ms ease, color 150ms ease;
        }

        .nav-link:hover {
            background-color: #1E293B;
            color: #FFFFFF;
        }

        .nav-link:focus-visible {
            outline: 2px solid #60A5FA;
            outline-offset: 2px;
        }

        .nav-link.active {
            background-color: #1E3A8A;
            color: #FFFFFF;
            font-weight: 600;
        }

        .sidebar-footer {
            padding: 16px 20px;
            border-top: 1px solid #1E293B;
            font-size: 0.75rem;
            color: #64748B;
        }

        /* Main Workspace Content */
        .workspace {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background-color: var(--bg-base);
        }

        /* Topbar Header */
        .topbar {
            background-color: var(--bg-surface);
            border-bottom: 1px solid var(--border-color);
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .system-status {
            font-size: 0.8125rem;
            color: var(--text-muted);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .system-pill {
            background: #EFF6FF;
            color: #1D4ED8;
            padding: 2px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.75rem;
            border: 1px solid #BFDBFE;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .user-role {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        .btn-logout {
            background-color: transparent;
            color: var(--text-muted);
            border: 1px solid var(--border-color);
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.8125rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            transition: all 150ms ease;
        }

        .btn-logout:hover {
            background-color: #FEE2E2;
            color: var(--btn-danger);
            border-color: #FCA5A5;
        }

        /* Main Content Container */
        .main-content {
            padding: 32px;
            flex-grow: 1;
            max-width: 1200px;
            width: 100%;
        }

        /* Module Header Section */
        .module-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .module-title h2 {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--text-main);
            letter-spacing: -0.3px;
        }

        .module-title p {
            font-size: 0.875rem;
            color: var(--text-muted);
            margin-top: 2px;
        }

        /* Buttons & Actions */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 0.875rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid transparent;
            transition: background-color 150ms ease, border-color 150ms ease;
            min-height: 38px;
        }

        .btn-primary {
            background-color: var(--brand-primary);
            color: #FFFFFF;
            border-color: var(--brand-primary);
        }

        .btn-primary:hover {
            background-color: var(--brand-primary-hover);
            border-color: var(--brand-primary-hover);
        }

        .btn-secondary {
            background-color: #FFFFFF;
            color: var(--text-main);
            border-color: var(--border-color);
        }

        .btn-secondary:hover {
            background-color: #F1F5F9;
            border-color: #CBD5E1;
        }

        .btn-danger-outline {
            background-color: transparent;
            color: var(--btn-danger);
            border-color: #FCA5A5;
        }

        .btn-danger-outline:hover {
            background-color: var(--status-inactive-bg);
            border-color: var(--btn-danger);
        }

        .btn-sm {
            padding: 4px 10px;
            font-size: 0.8125rem;
            min-height: 30px;
        }

        .btn:focus-visible {
            outline: 2px solid var(--border-focus);
            outline-offset: 2px;
        }

        /* Filter & Search Bar */
        .filter-panel {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
        }

        .search-form {
            display: flex;
            gap: 8px;
            flex-grow: 1;
            max-width: 500px;
        }

        .input-text, .input-select, .textarea-custom {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 6px;
            font-size: 0.875rem;
            color: var(--text-main);
            background-color: #FFFFFF;
            font-family: inherit;
            transition: border-color 150ms ease, box-shadow 150ms ease;
        }

        .input-text:focus, .input-select:focus, .textarea-custom:focus {
            outline: none;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }

        /* Table Card Container */
        .table-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.875rem;
        }

        .data-table th {
            background-color: #F8FAFC;
            color: var(--text-muted);
            font-weight: 600;
            padding: 12px 16px;
            border-bottom: 1px solid var(--border-color);
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.04em;
        }

        .data-table td {
            padding: 14px 16px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            color: var(--text-main);
        }

        .data-table tbody tr:hover {
            background-color: #F8FAFC;
        }

        .data-table tbody tr:last-child td {
            border-bottom: none;
        }

        .mono-tag {
            font-family: var(--font-mono);
            font-size: 0.8125rem;
            color: var(--text-muted);
            background: #F1F5F9;
            padding: 2px 6px;
            border-radius: 4px;
            border: 1px solid #E2E8F0;
        }

        /* Status Badge */
        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.2px;
        }

        .status-badge.active {
            background-color: var(--status-active-bg);
            color: var(--status-active-text);
            border: 1px solid #BBF7D0;
        }

        .status-badge.inactive {
            background-color: var(--status-inactive-bg);
            color: var(--status-inactive-text);
            border: 1px solid #FECACA;
        }

        /* Form Components */
        .form-card {
            background-color: var(--bg-surface);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 28px;
            max-width: 680px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.03);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .form-hint {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-top: 4px;
        }

        .form-error {
            font-size: 0.8125rem;
            color: var(--btn-danger);
            margin-top: 4px;
            font-weight: 500;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 28px;
            padding-top: 20px;
            border-top: 1px solid var(--border-color);
        }

        /* Alert notifications */
        .alert {
            padding: 12px 16px;
            border-radius: 6px;
            font-size: 0.875rem;
            margin-bottom: 20px;
            font-weight: 500;
            border: 1px solid transparent;
        }

        .alert-success {
            background-color: #ECFDF5;
            color: #065F46;
            border-color: #A7F3D0;
        }

        .alert-error {
            background-color: #FEF2F2;
            color: #991B1B;
            border-color: #FECACA;
        }

        /* Empty State */
        .empty-state {
            padding: 48px 24px;
            text-align: center;
            color: var(--text-muted);
        }

        .empty-state h3 {
            font-size: 1.1rem;
            color: var(--text-main);
            margin-bottom: 6px;
        }

        .empty-state p {
            font-size: 0.875rem;
            margin-bottom: 16px;
        }

        /* Responsive Breakpoints */
        @media (max-width: 820px) {
            .app-shell {
                flex-direction: column;
            }
            .sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #1E293B;
            }
            .sidebar-nav {
                flex-direction: row;
                flex-wrap: wrap;
            }
            .main-content {
                padding: 20px 16px;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Loncat ke konten utama</a>

    <div class="app-shell">
        <!-- Sidebar Navigation -->
        <aside class="sidebar" aria-label="Navigasi Utama">
            <div class="sidebar-brand">
                <div class="brand-badge" aria-hidden="true">TIK</div>
                <div class="brand-info">
                    <h1>Portal IAM</h1>
                    <p>Politeknik Negeri Jakarta</p>
                </div>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-label">Master Data</div>
                <a href="<?= BASE_URL ?>/accounts" class="nav-link <?= $currentNav === 'accounts' ? 'active' : '' ?>">
                    <span>Akun Pengguna</span>
                </a>
                <?php if (Auth::isAdmin()): ?>
                    <a href="<?= BASE_URL ?>/account-type" class="nav-link <?= $currentNav === 'account_type' ? 'active' : '' ?>">
                        <span>Tipe Akun</span>
                    </a>
                    <a href="<?= BASE_URL ?>/actions" class="nav-link <?= $currentNav === 'actions' ? 'active' : '' ?>">
                        <span>Jenis Aksi</span>
                    </a>
                <?php endif; ?>
            </nav>

            <div class="sidebar-footer">
                <p>Database: <strong>PBL_TI_2025_C_Kelompok1</strong></p>
                <p>Versi MVC Native</p>
            </div>
        </aside>

        <!-- Main Workspace -->
        <div class="workspace">
            <!-- Topbar Header -->
            <header class="topbar">
                <div class="system-status">
                    <span class="system-pill">Sistem Aktif</span>
                    <span>Modul Manajemen Identitas & Hak Akses</span>
                </div>

                <div class="user-menu">
                    <div class="user-details">
                        <div class="user-name">
                            <?= htmlspecialchars($currentUser['name'] ?? 'Pengguna') ?>
                            <span class="system-pill" style="margin-left: 6px; font-size: 0.7rem;"><?= htmlspecialchars($currentUser['role'] ?? 'Guest') ?></span>
                        </div>
                        <div class="user-role"><?= htmlspecialchars($currentUser['email'] ?? '') ?></div>
                    </div>
                    <a href="<?= BASE_URL ?>/logout" class="btn-logout">Logout</a>
                </div>
            </header>

            <!-- Main Content Area -->
            <main id="main-content" class="main-content">
