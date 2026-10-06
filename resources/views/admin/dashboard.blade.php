@php
    $currentUser = Auth::user();
    $isSuper = $currentUser->role === 'superadmin';
    $userPerms = is_array($currentUser->permissions) ? $currentUser->permissions : (json_decode($currentUser->permissions ?? '[]', true) ?: []);
    $hasAccess = function($tab) use ($isSuper, $userPerms) {
        return $isSuper || in_array('all', $userPerms) || in_array($tab, $userPerms);
    };
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin Dashboard | {{ $site['site_name'] ?? 'Tenderfold' }}</title>

    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-sidebar-w: 260px;
            --admin-dark-bg: #0b1e36;
            --admin-dark-sidebar: #071526;
            --admin-border: rgba(255,255,255,0.08);
            --admin-text-muted: rgba(255,255,255,0.5);
            --admin-input-bg: rgba(255,255,255,0.04);
            --mint-green: #5ccbb3;
            --primary-teal: #0085C8;
            --primary-teal-dark: #071A2E;
            --accent-orange: #ff6b6b;
            --accent-blue: #0085C8;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-sm: 6px;
        }
        
        body {
            background-color: var(--admin-dark-bg);
            color: #ffffff;
            font-family: 'Outfit', sans-serif;
            margin: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--admin-sidebar-w);
            background-color: var(--admin-dark-sidebar);
            border-right: 1px solid var(--admin-border);
            display: flex;
            flex-direction: column;
            padding: 1.5rem 0;
            flex-shrink: 0;
            overflow-y: auto;
            max-height: 100vh;
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), padding 0.3s ease, border-color 0.3s ease;
        }

        .sidebar-brand {
            padding: 0 1.5rem;
            margin-bottom: 2.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sidebar-brand svg {
            width: 36px;
            height: 36px;
        }

        .sidebar-brand span {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
            flex-grow: 1;
        }

        .menu-item button, .menu-item a {
            width: 100%;
            background: none;
            border: none;
            color: var(--admin-text-muted);
            padding: 1rem 1.5rem;
            text-align: left;
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
            text-decoration: none;
            box-sizing: border-box;
        }

        .menu-item button:hover, .menu-item button.active, .menu-item a:hover, .menu-item a.active {
            color: var(--mint-green);
            background-color: rgba(92, 203, 179, 0.05);
            border-left-color: var(--mint-green);
        }

        .sidebar-footer {
            padding: 0 1.5rem;
        }

        .btn-logout {
            width: 100%;
            background-color: rgba(232, 92, 36, 0.1);
            color: #ff6b6b;
            border: 1px solid rgba(255, 107, 107, 0.2);
            padding: 0.75rem;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background-color: rgba(255, 107, 107, 0.2);
        }

        /* Main Content Panel */
        .main-panel {
            flex: 1 1 0%;
            min-width: 0;
            min-height: 0;
            height: 100vh;
            max-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .admin-header {
            height: 70px;
            border-bottom: 1px solid var(--admin-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            flex-shrink: 0;
        }

        .admin-header h1 {
            font-size: 1.5rem;
            margin: 0;
            font-weight: 600;
        }

        .admin-user {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
        }

        .admin-user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: var(--primary-teal);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
        }

        .admin-content {
            padding: 2rem;
            overflow-y: scroll;
            overflow-x: hidden;
            flex: 1 1 0%;
            min-height: 0;
            height: calc(100vh - 70px);
            box-sizing: border-box;
            -webkit-overflow-scrolling: touch;
            scroll-behavior: smooth;
        }

        /* Tab Content Panel */
        .tab-panel {
            display: none;
            width: 100%;
            box-sizing: border-box;
        }

        .tab-panel.active {
            display: block;
            animation: fadeIn 0.4s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Cards & Forms */
        .admin-card {
            background: rgba(255,255,255,0.02);
            border: 1px solid var(--admin-border);
            border-radius: var(--radius-lg);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .admin-card h2 {
            font-size: 1.25rem;
            margin-top: 0;
            margin-bottom: 1.5rem;
            border-bottom: 1px solid var(--admin-border);
            padding-bottom: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            color: rgba(255,255,255,0.7);
            font-weight: 500;
        }

        .form-control {
            background-color: var(--admin-input-bg);
            border: 1px solid var(--admin-border);
            color: #ffffff;
            width: 100%;
            padding: 0.75rem 1rem;
            border-radius: var(--radius-md);
            font-family: 'Outfit', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--mint-green);
            background-color: rgba(255,255,255,0.06);
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .btn-submit {
            background-color: var(--mint-green);
            color: var(--primary-teal-dark);
            border: none;
            padding: 0.75rem 2rem;
            border-radius: var(--radius-md);
            font-weight: 600;
            cursor: pointer;
            font-family: 'Outfit', sans-serif;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #4ebfa7;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(92, 203, 179, 0.3);
        }

        /* Tables & Action Styles */
        .admin-table-wrap {
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--admin-border);
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 0.9rem;
        }

        .admin-table th {
            background-color: rgba(255,255,255,0.03);
            padding: 1rem;
            border-bottom: 1px solid var(--admin-border);
            font-weight: 600;
            color: var(--mint-green);
        }

        .admin-table td {
            padding: 1rem;
            border-bottom: 1px solid var(--admin-border);
            vertical-align: middle;
        }

        .admin-table tr:last-child td {
            border-bottom: none;
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            background: none;
            border: none;
            cursor: pointer;
            padding: 5px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .btn-action.edit {
            color: #5ccbb3;
            background-color: rgba(92, 203, 179, 0.1);
        }

        .btn-action.edit:hover {
            background-color: rgba(92, 203, 179, 0.25);
        }

        .btn-action.delete {
            color: #ff6b6b;
            background-color: rgba(255, 107, 107, 0.1);
        }

        .btn-action.delete:hover {
            background-color: rgba(255, 107, 107, 0.25);
        }

        /* Success Banner */
        .alert-success {
            background-color: rgba(92, 203, 179, 0.1);
            border: 1px solid rgba(92, 203, 179, 0.3);
            color: var(--mint-green);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Image upload helpers */
        .image-preview-container {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-top: 5px;
        }

        .image-preview {
            width: 60px;
            height: 60px;
            border-radius: 6px;
            border: 1px solid var(--admin-border);
            background-color: rgba(255,255,255,0.02);
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .badge-webp {
            font-size: 0.75rem;
            background-color: var(--primary-teal);
            color: white;
            padding: 2px 6px;
            border-radius: 4px;
            font-weight: 600;
        }
        .badge {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            display: inline-block;
        }

        /* Force all headings and instructions in backend to be white/visible */
        h1, h2, h3, h4, h5, h6,
        .admin-header h1, .sidebar-brand span, 
        .admin-card h2, .admin-card h3, .admin-card h4,
        .admin-content h1, .admin-content h2, .admin-content h3, .admin-content h4, .admin-content h5, .admin-content h6,
        #service-form-title-text, #blog-form-title-text {
            color: #ffffff !important;
        }
        
        .admin-content p:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-content span:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-content label:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-content li:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-content small:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-content legend:not(.ck-editor *):not(.badge):not(.badge *),
        .admin-card p:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-card span:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-card label:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-card li:not(.ck-editor *):not(.badge):not(.badge *), 
        .admin-card small:not(.ck-editor *):not(.badge):not(.badge *),
        .admin-table td:not(.badge):not(.badge *), 
        .admin-table th,
        .admin-table strong:not(.badge):not(.badge *) {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .admin-content code, .admin-card code {
            color: #5ccbb3 !important;
            background-color: rgba(92, 203, 179, 0.1) !important;
            padding: 2px 6px !important;
            border-radius: 4px !important;
        }

        .admin-table td, .admin-table th {
            color: #ffffff !important;
        }

        .admin-table th {
            color: var(--mint-green) !important;
        }

        .admin-table small, .admin-table td small {
            color: rgba(255, 255, 255, 0.6) !important;
        }

        .admin-card a {
            color: var(--mint-green) !important;
        }
        .admin-card a:hover {
            color: #4ebfa7 !important;
        }

        /* Toast Notification Styles */
        .ai-toast {
            background-color: #0d1718;
            border: 1px solid rgba(168, 85, 247, 0.3);
            box-shadow: 0 4px 20px rgba(168, 85, 247, 0.2);
            border-radius: var(--radius-md);
            color: #ffffff;
            padding: 1rem;
            width: 320px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: toastSlideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        @keyframes toastSlideIn {
            from { transform: translateX(120%) translateY(20px); opacity: 0; }
            to { transform: translateX(0) translateY(0); opacity: 1; }
        }
        .ai-toast-close {
            position: absolute;
            top: 8px;
            right: 8px;
            background: none;
            border: none;
            color: var(--admin-text-muted);
            cursor: pointer;
            font-size: 1.1rem;
            line-height: 1;
        }
        .ai-toast-close:hover {
            color: #ff6b6b;
        }
        .ai-toast-spinner {
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top-color: #a855f7;
            border-bottom-color: #6366f1;
            border-radius: 50%;
            animation: aiSpin 1s linear infinite;
            flex-shrink: 0;
            margin-top: 3px;
        }
        .ai-toast-success-icon {
            color: var(--mint-green);
            flex-shrink: 0;
            margin-top: 3px;
            display: flex;
            align-items: center;
        }
        .ai-toast-error-icon {
            color: #ff6b6b;
            flex-shrink: 0;
            margin-top: 3px;
            display: flex;
            align-items: center;
        }
        .ai-toast-content {
            flex-grow: 1;
            padding-right: 15px;
        }
        .ai-toast-title {
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 2px;
            color: #ffffff !important;
        }
        .ai-toast-message {
            font-size: 0.8rem;
            color: rgba(255,255,255,0.7) !important;
            margin: 0;
            line-height: 1.4;
        }

        /* Keep CKEditor text dark so users can see what they type in the editor box! */
        .ck-editor__editable, .ck-editor__editable * {
            color: #111111 !important;
        }

        /* AI Generator styles */
        .btn-ai-sparkle {
            background: linear-gradient(135deg, #a855f7, #6366f1) !important;
            color: #ffffff !important;
            border: none !important;
            padding: 0.5rem 1rem !important;
            border-radius: var(--radius-md) !important;
            font-weight: 600 !important;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.85rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(168, 85, 247, 0.2);
        }
        .btn-ai-sparkle:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(168, 85, 247, 0.4);
            filter: brightness(1.1);
        }
        .ai-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.75);
            backdrop-filter: blur(8px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .ai-modal-overlay.show {
            display: flex;
            opacity: 1;
        }
        .ai-modal-card {
            background-color: #0d1718;
            border: 1px solid rgba(168, 85, 247, 0.3);
            box-shadow: 0 10px 30px rgba(168, 85, 247, 0.15), 0 0 40px rgba(99, 102, 241, 0.05);
            border-radius: var(--radius-lg);
            width: 90%;
            max-width: 550px;
            padding: 2rem;
            animation: aiSlideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }
        @keyframes aiSlideIn {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .ai-modal-card h3 {
            margin-top: 0;
            font-size: 1.35rem;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
            border-bottom: 1px solid var(--admin-border);
            padding-bottom: 0.75rem;
            margin-bottom: 1.5rem;
        }
        .ai-modal-card h3 i {
            color: #a855f7;
        }
        .ai-modal-close {
            position: absolute;
            right: 1.5rem;
            top: 1.5rem;
            background: none;
            border: none;
            color: var(--admin-text-muted);
            font-size: 1.5rem;
            cursor: pointer;
            transition: color 0.2s;
        }
        .ai-modal-close:hover {
            color: #ff6b6b;
        }
        .ai-loader-container {
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 1rem;
            text-align: center;
        }
        .ai-sparkle-loader {
            width: 60px;
            height: 60px;
            position: relative;
            margin-bottom: 1.5rem;
        }
        .ai-sparkle-loader::before, .ai-sparkle-loader::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 3px solid transparent;
            border-top-color: #a855f7;
            border-bottom-color: #6366f1;
            animation: aiSpin 1.5s linear infinite;
        }
        .ai-sparkle-loader::after {
            width: 40px;
            height: 40px;
            top: 10px;
            left: 10px;
            border-top-color: #5ccbb3;
            border-bottom-color: #e85c24;
            animation: aiSpinReverse 1s linear infinite;
        }
        @keyframes aiSpin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        @keyframes aiSpinReverse {
            0% { transform: rotate(360deg); }
            100% { transform: rotate(0deg); }
        }
        .ai-loader-text {
            font-size: 1.05rem;
            font-weight: 500;
            color: #ffffff;
            margin: 0;
        }
        .ai-loader-subtext {
            font-size: 0.85rem;
            color: var(--admin-text-muted);
            margin: 5px 0 0 0;
        }
        .ai-img-preview-box {
            margin-top: 1rem;
            border: 1px solid var(--admin-border);
            border-radius: var(--radius-md);
            overflow: hidden;
            display: none;
            flex-direction: column;
            background: rgba(0,0,0,0.2);
        }
        .ai-img-preview-box img {
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            background: #000;
        }
        .ai-img-preview-box .info {
            padding: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-top: 1px solid var(--admin-border);
        }

        /* Custom Scrollbar for Main Content, Sidebar, Mobile Scroll, and Tables */
        .admin-content, .sidebar, .mobile-tabs-scroll, .admin-table-wrap {
            --scrollbar-thumb: var(--mint-green);
            --scrollbar-track: rgba(255, 255, 255, 0.02);
            scrollbar-color: var(--scrollbar-thumb) var(--scrollbar-track);
            scrollbar-width: thin;
        }
        @supports not (scrollbar-color: auto) {
            .admin-content::-webkit-scrollbar,
            .sidebar::-webkit-scrollbar,
            .mobile-tabs-scroll::-webkit-scrollbar,
            .admin-table-wrap::-webkit-scrollbar {
                width: 6px;
                height: 6px;
            }
            .admin-content::-webkit-scrollbar-thumb,
            .sidebar::-webkit-scrollbar-thumb,
            .mobile-tabs-scroll::-webkit-scrollbar-thumb,
            .admin-table-wrap::-webkit-scrollbar-thumb {
                background: var(--scrollbar-thumb);
                border-radius: 3px;
            }
            .admin-content::-webkit-scrollbar-track,
            .sidebar::-webkit-scrollbar-track,
            .mobile-tabs-scroll::-webkit-scrollbar-track,
            .admin-table-wrap::-webkit-scrollbar-track {
                background: var(--scrollbar-track);
            }
        }

        /* Mobile Hamburger & Close buttons */
        .mobile-toggle-btn {
            display: inline-flex;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--admin-border);
            color: #ffffff;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            align-items: center;
            justify-content: center;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
            width: 40px;
            height: 40px;
        }
        .mobile-toggle-btn:hover {
            background-color: rgba(255, 255, 255, 0.08);
            border-color: rgba(255, 255, 255, 0.2);
            color: var(--mint-green);
        }

        .sidebar-close-btn {
            display: none;
            background: none;
            border: none;
            color: var(--admin-text-muted);
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.25rem;
            align-items: center;
            justify-content: center;
            margin-left: auto;
            border-radius: var(--radius-sm);
            transition: color 0.2s;
        }
        .sidebar-close-btn:hover {
            color: #ff6b6b;
        }

        /* Mobile Backdrop */
        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            z-index: 1040;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .sidebar-backdrop.show {
            display: block;
            opacity: 1;
        }

        /* Mobile Swipe Tab Strip */
        .mobile-tabs-scroll {
            display: none;
            gap: 8px;
            overflow-x: auto;
            padding: 0.75rem 1rem;
            border-bottom: 1px solid var(--admin-border);
            background-color: var(--admin-dark-sidebar);
            white-space: nowrap;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none; /* Hide for firefox */
        }
        .mobile-tabs-scroll::-webkit-scrollbar {
            display: none; /* Hide for Chrome/Safari */
        }
        .mobile-tab-btn {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid var(--admin-border);
            color: var(--admin-text-muted);
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-family: 'Outfit', sans-serif;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }
        .mobile-tab-btn:hover, .mobile-tab-btn.active {
            color: var(--mint-green);
            background-color: rgba(92, 203, 179, 0.08);
            border-color: var(--mint-green);
        }

        @media (min-width: 992px) {
            body.sidebar-collapsed .sidebar {
                width: 0;
                padding-left: 0;
                padding-right: 0;
                border-right-color: transparent;
                overflow: hidden;
            }
            body.sidebar-collapsed {
                --admin-sidebar-w: 0px;
            }
        }

        /* Media Queries for Layout Responsiveness */
        @media (max-width: 991.98px) {
            body {
                flex-direction: column;
                height: 100vh;
                overflow: hidden;
            }
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1050;
                transform: translateX(-100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                height: 100%;
                box-shadow: 20px 0 30px rgba(0, 0, 0, 0.5);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar-close-btn {
                display: inline-flex;
            }
            .mobile-toggle-btn {
                display: inline-flex;
            }
            .mobile-tabs-scroll {
                display: flex;
            }
            .admin-header {
                padding: 0 1rem;
            }
            .admin-content {
                padding: 1.25rem;
            }
            .admin-card {
                padding: 1.25rem;
                margin-bottom: 1.25rem;
            }
            /* Form grid styling */
            .form-grid {
                grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr));
                gap: 1rem;
            }
            .ai-toast {
                max-width: calc(100vw - 40px);
            }
        }

        @media (max-width: 575.98px) {
            .admin-header h1 {
                font-size: 1.2rem;
            }
            .admin-user span {
                display: none; /* Hide user name on small mobile, keep avatar */
            }
            .admin-card h2 {
                font-size: 1.1rem;
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            .admin-card h2 button {
                width: 100%;
                justify-content: center;
            }
            .btn-submit {
                width: 100%;
                text-align: center;
            }
            /* Form fields */
            .form-group[style*="grid-column: span 2"] {
                grid-column: span 1 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="sidebar-brand" style="padding: 1.2rem 1rem;">
            <a href="{{ route('home') }}" target="_blank" class="logo" style="display: flex; align-items: center; gap: 8px; text-decoration: none; color: white;">
                @if(!empty($site['logo_path']))
                    <img src="{{ site_image($site['logo_path']) }}" alt="{{ $site['site_name'] ?? 'Tenderfold' }}" style="height: 38px; width: auto; object-fit: contain;">
                @else
                    <svg class="logo-icon" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg" style="stroke: #5ccbb3; stroke-width: 6; width: 32px; height: 32px; flex-shrink: 0;">
                        <circle cx="50" cy="50" r="40" stroke="currentColor" fill="none"/>
                        <path d="M50 25 V75 M25 50 H75" stroke="currentColor" stroke-width="12" stroke-linecap="round"/>
                    </svg>
                    <div style="text-align: left; display: flex; flex-direction: column;">
                        <span class="logo-text" style="font-size: 1.05rem; font-weight: 800; line-height: 1.1; color: white; letter-spacing: -0.5px;">{{ $site['site_name'] ?? 'Tenderfold' }}</span>
                        <span class="logo-tagline" style="font-size: 0.58rem; opacity: 0.8; font-weight: 600; color: #a5f3fc; line-height: 1;">{{ Str::limit($site['site_tagline'] ?? 'Freshness in Every Single Fold', 28) }}</span>
                    </div>
                @endif
            </a>
            <button class="sidebar-close-btn" onclick="toggleSidebar()" aria-label="Close Sidebar">
                <i data-lucide="x"></i>
            </button>
        </div>
        
        <ul class="sidebar-menu">
            @if($hasAccess('general'))<li class="menu-item"><a href="{{ route('admin.tab', 'general') }}" class="tab-btn {{ $activeTab === 'general' ? 'active' : '' }}" id="btn-general"><i data-lucide="settings"></i> General Settings</a></li>@endif
            @if($hasAccess('leads'))<li class="menu-item"><a href="{{ route('admin.tab', 'leads') }}" class="tab-btn {{ $activeTab === 'leads' ? 'active' : '' }}" id="btn-leads"><i data-lucide="inbox"></i> Leads Manager</a></li>@endif
            @if($hasAccess('livechat'))<li class="menu-item">
                <a href="{{ route('admin.tab', 'livechat') }}" class="tab-btn {{ $activeTab === 'livechat' ? 'active' : '' }}" id="btn-livechat" style="position: relative;">
                    <i data-lucide="message-square"></i> Live Chat Support
                    <span id="admin-chat-total-badge" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background-color: #ef4444; color: white; font-size: 0.65rem; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; display: none; align-items: center; justify-content: center;">0</span>
                </a>
            </li>@endif

            @php 
                $isAppearanceActive = in_array($activeTab, ['branding', 'slider', 'styling', 'pagecontents']); 
            @endphp
            @if($hasAccess('branding') || $hasAccess('slider') || $hasAccess('styling') || $hasAccess('pagecontents'))
                <li class="menu-item has-submenu {{ $isAppearanceActive ? 'open' : '' }}">
                    <a href="javascript:void(0)" class="tab-btn {{ $isAppearanceActive ? 'active' : '' }}" onclick="toggleSubmenu(this)" style="display: flex; align-items: center;">
                        <i data-lucide="layout"></i> Site Appearance & Content
                        <i data-lucide="chevron-down" class="submenu-arrow" style="width: 14px; height: 14px; margin-left: auto; transition: transform 0.2s;"></i>
                    </a>
                    <ul class="submenu-list" style="{{ $isAppearanceActive ? 'display: block;' : 'display: none;' }} padding-left: 0.75rem; list-style: none; margin: 0.25rem 0;">
                        @if($hasAccess('branding'))<li style="margin: 2px 0;"><a href="{{ route('admin.tab', 'branding') }}" class="tab-btn {{ $activeTab === 'branding' ? 'active' : '' }}" id="btn-branding" style="padding: 0.4rem 0.75rem; font-size: 0.82rem;"><i data-lucide="image" style="width: 14px; height: 14px;"></i> Branding & Logos</a></li>@endif
                        @if($hasAccess('slider'))<li style="margin: 2px 0;"><a href="{{ route('admin.tab', 'slider') }}" class="tab-btn {{ $activeTab === 'slider' ? 'active' : '' }}" id="btn-slider" style="padding: 0.4rem 0.75rem; font-size: 0.82rem;"><i data-lucide="sliders" style="width: 14px; height: 14px;"></i> Homepage Slider</a></li>@endif
                        @if($hasAccess('styling'))<li style="margin: 2px 0;"><a href="{{ route('admin.tab', 'styling') }}" class="tab-btn {{ $activeTab === 'styling' ? 'active' : '' }}" id="btn-styling" style="padding: 0.4rem 0.75rem; font-size: 0.82rem;"><i data-lucide="palette" style="width: 14px; height: 14px;"></i> Section Backgrounds</a></li>@endif
                        @if($hasAccess('pagecontents'))<li style="margin: 2px 0;"><a href="{{ route('admin.tab', 'pagecontents') }}" class="tab-btn {{ $activeTab === 'pagecontents' ? 'active' : '' }}" id="btn-pagecontents" style="padding: 0.4rem 0.75rem; font-size: 0.82rem;"><i data-lucide="file-text" style="width: 14px; height: 14px;"></i> Page Editor</a></li>@endif
                    </ul>
                </li>
            @endif

            @if($hasAccess('testimonials'))<li class="menu-item"><a href="{{ route('admin.tab', 'testimonials') }}" class="tab-btn {{ $activeTab === 'testimonials' ? 'active' : '' }}" id="btn-testimonials"><i data-lucide="users"></i> Testimonials</a></li>@endif
            @if($hasAccess('landing'))<li class="menu-item"><a href="{{ route('admin.tab', 'landing') }}" class="tab-btn {{ $activeTab === 'landing' ? 'active' : '' }}" id="btn-landing"><i data-lucide="sparkles"></i> AI Landing Pages</a></li>@endif
            @if($hasAccess('services'))<li class="menu-item"><a href="{{ route('admin.tab', 'services') }}" class="tab-btn {{ $activeTab === 'services' ? 'active' : '' }}" id="btn-services"><i data-lucide="layers"></i> Services Manager</a></li>@endif
            @if($hasAccess('cms_pages'))<li class="menu-item"><a href="{{ route('admin.tab', 'cms_pages') }}" class="tab-btn {{ $activeTab === 'cms_pages' ? 'active' : '' }}" id="btn-cms_pages"><i data-lucide="files"></i> CMS Pages Manager</a></li>@endif
            @if($hasAccess('blogs'))<li class="menu-item"><a href="{{ route('admin.tab', 'blogs') }}" class="tab-btn {{ $activeTab === 'blogs' ? 'active' : '' }}" id="btn-blogs"><i data-lucide="book-open"></i> Blog Manager</a></li>@endif
            @if($hasAccess('media'))<li class="menu-item"><a href="{{ route('admin.tab', 'media') }}" class="tab-btn {{ $activeTab === 'media' ? 'active' : '' }}" id="btn-media"><i data-lucide="hard-drive"></i> Media Manager</a></li>@endif
            @if($hasAccess('seo'))<li class="menu-item"><a href="{{ route('admin.tab', 'seo') }}" class="tab-btn {{ $activeTab === 'seo' ? 'active' : '' }}" id="btn-seo"><i data-lucide="trending-up"></i> SEO Optimizer</a></li>@endif
            @if($hasAccess('analytics'))<li class="menu-item"><a href="{{ route('admin.tab', 'analytics') }}" class="tab-btn {{ $activeTab === 'analytics' ? 'active' : '' }}" id="btn-analytics"><i data-lucide="bar-chart-3"></i> Google Analytics & GMB</a></li>@endif
            <li class="menu-item"><a href="{{ route('admin.tab', 'internal_notifications') }}" class="tab-btn {{ $activeTab === 'internal_notifications' ? 'active' : '' }}" id="btn-internal_notifications" style="position: relative;"><i data-lucide="bell"></i> Internal Logs & Errors <span id="admin-internal-unread-badge" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background-color: #3b82f6; color: white; font-size: 0.65rem; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; display: none; align-items: center; justify-content: center;">0</span></a></li>
            @if($hasAccess('seo'))<li class="menu-item"><a href="{{ route('admin.tab', 'notifications') }}" class="tab-btn {{ $activeTab === 'notifications' ? 'active' : '' }}" id="btn-notifications"><i data-lucide="send"></i> Push Notifications</a></li>@endif
            @if($isSuper)<li class="menu-item"><a href="{{ route('admin.tab', 'users') }}" class="tab-btn {{ $activeTab === 'users' ? 'active' : '' }}" id="btn-users"><i data-lucide="user"></i> Admin Users</a></li>@endif
        </ul>

        <div class="sidebar-footer">
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn-logout" onclick="localStorage.removeItem('active_tab')">
                    <i data-lucide="log-out"></i> Log Out
                </button>
            </form>
        </div>
    </div>

    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop" id="sidebar-backdrop" onclick="toggleSidebar()"></div>

    <!-- Main Content Panel -->
    <div class="main-panel">
        <div class="admin-header">
            <div style="display: flex; align-items: center; gap: 10px;">
                <button class="mobile-toggle-btn" onclick="toggleSidebar()" aria-label="Toggle Sidebar">
                    <i data-lucide="menu"></i>
                </button>
                <h1>CMS Control Panel</h1>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <!-- Internal Notifications Bell Dropdown -->
                <div style="position: relative;">
                    <button type="button" onclick="toggleNotificationDropdown()" id="nav-bell-btn" style="background: rgba(255,255,255,0.06); border: 1px solid var(--admin-border); color: #ffffff; padding: 6px 12px; border-radius: 20px; font-size: 0.85rem; display: flex; align-items: center; gap: 6px; cursor: pointer; position: relative;">
                        <i data-lucide="bell" style="width: 16px; height: 16px; color: var(--mint-green);"></i>
                        <span style="font-size: 0.8rem; font-weight: 600;">Alerts</span>
                        <span id="nav-bell-count" style="background-color: #ef4444; color: white; font-size: 0.65rem; font-weight: 700; padding: 2px 6px; border-radius: 10px; display: none;">0</span>
                    </button>
                    <!-- Bell Popover Menu -->
                    <div id="nav-bell-dropdown" style="display: none; position: absolute; right: 0; top: 120%; width: 340px; background-color: var(--admin-dark-sidebar); border: 1px solid var(--admin-border); border-radius: var(--radius-md); box-shadow: 0 10px 25px rgba(0,0,0,0.5); z-index: 2500;">
                        <div style="padding: 0.75rem 1rem; border-bottom: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
                            <strong style="font-size: 0.85rem; color: #ffffff;">Internal System Notifications</strong>
                            <a href="{{ route('admin.tab', 'internal_notifications') }}" style="font-size: 0.75rem; color: var(--mint-green); text-decoration: none;">View All →</a>
                        </div>
                        <div id="nav-bell-items-list" style="max-height: 260px; overflow-y: auto; display: flex; flex-direction: column;">
                            <div style="padding: 1.5rem; text-align: center; color: var(--admin-text-muted); font-size: 0.8rem;">Loading notifications...</div>
                        </div>
                        <div style="padding: 0.5rem 1rem; border-top: 1px solid var(--admin-border); text-align: center;">
                            <button type="button" onclick="markAllNotificationsRead()" style="background: none; border: none; color: #94a3b8; font-size: 0.75rem; cursor: pointer;">Mark all as read</button>
                        </div>
                    </div>
                </div>

                <button class="btn-ai-sparkle" style="padding: 6px 12px; font-size: 0.8rem; height: auto;" onclick="openProfileModal()">
                    <i data-lucide="user" style="width: 14px; height: 14px; vertical-align: middle;"></i> My Profile
                </button>
                <div class="admin-user">
                    <span>{{ Auth::user() ? Auth::user()->name : 'Admin' }}</span>
                    <div class="admin-user-avatar">{{ Auth::user() ? substr(Auth::user()->name, 0, 1) : 'A' }}</div>
                </div>
            </div>
        </div>

        <!-- Mobile Quick-Switch Tabs -->
        <div class="mobile-tabs-scroll">
            @if($hasAccess('general'))<a href="{{ route('admin.tab', 'general') }}" class="mobile-tab-btn {{ $activeTab === 'general' ? 'active' : '' }}" id="m-btn-general"><i data-lucide="settings" style="width: 14px; height: 14px;"></i> General</a>@endif
            @if($hasAccess('leads'))<a href="{{ route('admin.tab', 'leads') }}" class="mobile-tab-btn {{ $activeTab === 'leads' ? 'active' : '' }}" id="m-btn-leads"><i data-lucide="inbox" style="width: 14px; height: 14px;"></i> Leads</a>@endif
            @if($hasAccess('livechat'))<a href="{{ route('admin.tab', 'livechat') }}" class="mobile-tab-btn {{ $activeTab === 'livechat' ? 'active' : '' }}" id="m-btn-livechat" style="position: relative;"><i data-lucide="message-square" style="width: 14px; height: 14px;"></i> Live Chat <span id="admin-chat-mobile-badge" style="position: absolute; right: 2px; top: 2px; background-color: #ef4444; color: white; font-size: 0.6rem; font-weight: 700; width: 14px; height: 14px; border-radius: 50%; display: none; align-items: center; justify-content: center;">0</span></a>@endif
            @if($hasAccess('branding'))<a href="{{ route('admin.tab', 'branding') }}" class="mobile-tab-btn {{ $activeTab === 'branding' ? 'active' : '' }}" id="m-btn-branding"><i data-lucide="image" style="width: 14px; height: 14px;"></i> Branding</a>@endif
            @if($hasAccess('slider'))<a href="{{ route('admin.tab', 'slider') }}" class="mobile-tab-btn {{ $activeTab === 'slider' ? 'active' : '' }}" id="m-btn-slider"><i data-lucide="sliders" style="width: 14px; height: 14px;"></i> Slider</a>@endif
            @if($hasAccess('testimonials'))<a href="{{ route('admin.tab', 'testimonials') }}" class="mobile-tab-btn {{ $activeTab === 'testimonials' ? 'active' : '' }}" id="m-btn-testimonials"><i data-lucide="users" style="width: 14px; height: 14px;"></i> Testimonials</a>@endif
            @if($hasAccess('styling'))<a href="{{ route('admin.tab', 'styling') }}" class="mobile-tab-btn {{ $activeTab === 'styling' ? 'active' : '' }}" id="m-btn-styling"><i data-lucide="palette" style="width: 14px; height: 14px;"></i> Styling</a>@endif
            @if($hasAccess('pagecontents'))<a href="{{ route('admin.tab', 'pagecontents') }}" class="mobile-tab-btn {{ $activeTab === 'pagecontents' ? 'active' : '' }}" id="m-btn-pagecontents"><i data-lucide="file-text" style="width: 14px; height: 14px;"></i> Pages</a>@endif
            @if($hasAccess('landing'))<a href="{{ route('admin.tab', 'landing') }}" class="mobile-tab-btn {{ $activeTab === 'landing' ? 'active' : '' }}" id="m-btn-landing"><i data-lucide="layout" style="width: 14px; height: 14px;"></i> AI Pages</a>@endif
            @if($hasAccess('services'))<a href="{{ route('admin.tab', 'services') }}" class="mobile-tab-btn {{ $activeTab === 'services' ? 'active' : '' }}" id="m-btn-services"><i data-lucide="layers" style="width: 14px; height: 14px;"></i> Services</a>@endif
            @if($hasAccess('cms_pages'))<a href="{{ route('admin.tab', 'cms_pages') }}" class="mobile-tab-btn {{ $activeTab === 'cms_pages' ? 'active' : '' }}" id="m-btn-cms_pages"><i data-lucide="files" style="width: 14px; height: 14px;"></i> CMS Pages</a>@endif
            @if($hasAccess('blogs'))<a href="{{ route('admin.tab', 'blogs') }}" class="mobile-tab-btn {{ $activeTab === 'blogs' ? 'active' : '' }}" id="m-btn-blogs"><i data-lucide="book-open" style="width: 14px; height: 14px;"></i> Blogs</a>@endif
            @if($hasAccess('media'))<a href="{{ route('admin.tab', 'media') }}" class="mobile-tab-btn {{ $activeTab === 'media' ? 'active' : '' }}" id="m-btn-media"><i data-lucide="hard-drive" style="width: 14px; height: 14px;"></i> Media</a>@endif
            {{-- @if($hasAccess('analytics'))<a href="{{ route('admin.tab', 'analytics') }}" class="mobile-tab-btn {{ $activeTab === 'analytics' ? 'active' : '' }}" id="m-btn-analytics"><i data-lucide="bar-chart-2" style="width: 14px; height: 14px;"></i> Analytics</a>@endif --}}
            @if($hasAccess('seo'))<a href="{{ route('admin.tab', 'seo') }}" class="mobile-tab-btn {{ $activeTab === 'seo' ? 'active' : '' }}" id="m-btn-seo"><i data-lucide="trending-up" style="width: 14px; height: 14px;"></i> SEO</a>@endif
            @if($hasAccess('analytics'))<a href="{{ route('admin.tab', 'analytics') }}" class="mobile-tab-btn {{ $activeTab === 'analytics' ? 'active' : '' }}" id="m-btn-analytics"><i data-lucide="bar-chart-3" style="width: 14px; height: 14px;"></i> Analytics</a>@endif
            @if($hasAccess('seo'))<a href="{{ route('admin.tab', 'notifications') }}" class="mobile-tab-btn {{ $activeTab === 'notifications' ? 'active' : '' }}" id="m-btn-notifications"><i data-lucide="bell" style="width: 14px; height: 14px;"></i> Alerts</a>@endif
            @if($isSuper)<a href="{{ route('admin.tab', 'users') }}" class="mobile-tab-btn {{ $activeTab === 'users' ? 'active' : '' }}" id="m-btn-users"><i data-lucide="user" style="width: 14px; height: 14px;"></i> Admins</a>@endif

        </div>


        <div class="admin-content">
            <!-- Success Banner -->
            @if(session('success'))
                <div class="alert-success">
                    <i data-lucide="check-circle-2"></i>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert-success" style="background-color: rgba(255,107,107,0.1); border-color: rgba(255,107,107,0.3); color: #ff6b6b;">
                    <i data-lucide="alert-circle"></i>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            <!-- 1. GENERAL & CONTACT SETTINGS TAB -->
            <div class="tab-panel @if($activeTab === 'general') active @endif" id="tab-general">
                <form action="{{ route('admin.parameters.update') }}" method="POST">
                    @csrf
                    
                    <div class="admin-card">
                        <h2>Contact Information</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" class="form-control" value="{{ $site['phone'] ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" class="form-control" value="{{ $site['email'] ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="whatsapp_number">WhatsApp Number (with country code, no +)</label>
                                <input type="text" id="whatsapp_number" name="whatsapp_number" class="form-control" value="{{ $site['whatsapp_number'] ?? '' }}" required>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 1.25rem;">
                            <label for="address">Physical Address</label>
                            <input type="text" id="address" name="address" class="form-control" value="{{ $site['address'] ?? '' }}" required>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Social Media Profiles & Custom SVG Icons</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">Configure your official social media profile URLs and optional custom SVG icon code.</p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="social_facebook">Facebook URL</label>
                                <input type="url" id="social_facebook" name="social_facebook" class="form-control" value="{{ $site['social_facebook'] ?? '' }}" placeholder="https://facebook.com/yourpage">
                            </div>
                            <div class="form-group">
                                <label for="social_facebook_svg">Facebook Custom SVG Icon (optional)</label>
                                <input type="text" id="social_facebook_svg" name="social_facebook_svg" class="form-control" value="{{ $site['social_facebook_svg'] ?? '' }}" placeholder="<svg>...</svg>">
                            </div>

                            <div class="form-group">
                                <label for="social_instagram">Instagram URL</label>
                                <input type="url" id="social_instagram" name="social_instagram" class="form-control" value="{{ $site['social_instagram'] ?? '' }}" placeholder="https://instagram.com/yourusername">
                            </div>
                            <div class="form-group">
                                <label for="social_instagram_svg">Instagram Custom SVG Icon (optional)</label>
                                <input type="text" id="social_instagram_svg" name="social_instagram_svg" class="form-control" value="{{ $site['social_instagram_svg'] ?? '' }}" placeholder="<svg>...</svg>">
                            </div>

                            <div class="form-group">
                                <label for="social_linkedin">LinkedIn URL</label>
                                <input type="url" id="social_linkedin" name="social_linkedin" class="form-control" value="{{ $site['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                            <div class="form-group">
                                <label for="social_linkedin_svg">LinkedIn Custom SVG Icon (optional)</label>
                                <input type="text" id="social_linkedin_svg" name="social_linkedin_svg" class="form-control" value="{{ $site['social_linkedin_svg'] ?? '' }}" placeholder="<svg>...</svg>">
                            </div>

                            <div class="form-group">
                                <label for="social_twitter">Twitter / X URL</label>
                                <input type="url" id="social_twitter" name="social_twitter" class="form-control" value="{{ $site['social_twitter'] ?? '' }}" placeholder="https://twitter.com/yourhandle">
                            </div>
                            <div class="form-group">
                                <label for="social_twitter_svg">Twitter / X Custom SVG Icon (optional)</label>
                                <input type="text" id="social_twitter_svg" name="social_twitter_svg" class="form-control" value="{{ $site['social_twitter_svg'] ?? '' }}" placeholder="<svg>...</svg>">
                            </div>

                            <div class="form-group">
                                <label for="social_youtube">YouTube Channel URL</label>
                                <input type="url" id="social_youtube" name="social_youtube" class="form-control" value="{{ $site['social_youtube'] ?? '' }}" placeholder="https://youtube.com/@yourchannel">
                            </div>
                            <div class="form-group">
                                <label for="social_youtube_svg">YouTube Custom SVG Icon (optional)</label>
                                <input type="text" id="social_youtube_svg" name="social_youtube_svg" class="form-control" value="{{ $site['social_youtube_svg'] ?? '' }}" placeholder="<svg>...</svg>">
                            </div>

                            <div class="form-group">
                                <label for="social_practo">Google Business Profile / Maps URL</label>
                                <input type="url" id="social_practo" name="social_practo" class="form-control" value="{{ $site['social_practo'] ?? '' }}" placeholder="https://maps.app.goo.gl/tenderfold">
                            </div>
                            <div class="form-group">
                                <label for="social_practo_svg">Google Maps / Review Custom SVG Icon (optional)</label>
                                <input type="text" id="social_practo_svg" name="social_practo_svg" class="form-control" value="{{ $site['social_practo_svg'] ?? '' }}" placeholder="<svg>...</svg>">
                            </div>
                        </div>
                    </div>


                    <div class="admin-card">
                        <h2>SEO & Tracking Codes</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">Configure global tracking and verification tags.</p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="google_tag_id">Google Tag ID / GTM ID / GA4 Measurement ID</label>
                                <input type="text" id="google_tag_id" name="google_tag_id" class="form-control" value="{{ $site['google_tag_id'] ?? '' }}" placeholder="e.g., G-XXXXXXXXXX or GTM-XXXXXXX">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 1.25rem;">
                            <label for="header_scripts">Custom Tracking Scripts (inserted before &lt;/head&gt;)</label>
                            <textarea id="header_scripts" name="header_scripts" class="form-control" rows="4" placeholder="<!-- Paste Facebook Pixel, custom GTM script tags, etc. -->">{{ $site['header_scripts'] ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Google reCAPTCHA v2 Settings</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Protects all contact and inquiry forms on the website from automated spam. 
                            <a href="https://www.google.com/recaptcha/admin" target="_blank" style="color: var(--accent-orange); text-decoration: underline; font-weight: 500;">
                                Learn how to generate Google reCAPTCHA v2 checkbox keys &rarr;
                            </a>
                        </p>
                        
                        <div class="form-grid">
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="recaptcha_enabled" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="checkbox" id="recaptcha_enabled" name="recaptcha_enabled" value="1" @checked(($site['recaptcha_enabled'] ?? '0') == '1') style="width: 18px; height: 18px; cursor: pointer;">
                                    <strong>Enable Google reCAPTCHA Checkbox on Forms</strong>
                                </label>
                            </div>
                            
                            <div class="form-group">
                                <label for="recaptcha_site_key">reCAPTCHA Site Key</label>
                                <input type="text" id="recaptcha_site_key" name="recaptcha_site_key" class="form-control" value="{{ $site['recaptcha_site_key'] ?? '' }}" placeholder="Paste Google Site Key (e.g. 6Ld...)">
                            </div>
                            
                            <div class="form-group">
                                <label for="recaptcha_secret_key">reCAPTCHA Secret Key</label>
                                <input type="text" id="recaptcha_secret_key" name="recaptcha_secret_key" class="form-control" value="{{ $site['recaptcha_secret_key'] ?? '' }}" placeholder="Paste Google Secret Key">
                            </div>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Google Gemini AI & Business Settings</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Provide a Google Gemini API Key and your Google My Business link to enable AI generation and reviews sync.
                        </p>
                        <div class="form-grid">
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="gemini_api_key">Gemini API Key</label>
                                <div style="display: flex; gap: 10px; align-items: center;">
                                    <input type="password" id="gemini_api_key" name="gemini_api_key" class="form-control" value="{{ $site['gemini_api_key'] ?? '' }}" placeholder="Paste your Gemini API Key here..." style="flex-grow: 1;">
                                    <button type="button" onclick="testAiConnection()" id="btn-test-ai" class="btn" style="background-color: rgba(92, 203, 179, 0.15); color: var(--mint-green); border: 1px solid var(--mint-green); padding: 0.6rem 1.25rem; font-size: 0.85rem; font-weight: 600; cursor: pointer; white-space: nowrap; height: auto;">
                                        <i data-lucide="zap" style="width: 14px; height: 14px; vertical-align: middle;"></i> Test Connection
                                    </button>
                                </div>
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    Get an API Key from the <a href="https://aistudio.google.com/" target="_blank" style="color: var(--mint-green); text-decoration: underline;">Google AI Studio Console</a>.
                                </span>
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ai_model">AI Engine Model</label>
                                <select id="ai_model" name="ai_model" class="form-control" style="padding: 0.6rem; height: auto; background-color: var(--admin-input-bg); color: #ffffff;">
                                    @php $currentModel = $site['ai_model'] ?? 'gemini-2.5-flash'; @endphp
                                    <option value="gemini-2.5-flash" @selected($currentModel === 'gemini-2.5-flash' || $currentModel === 'gemini-2.0-flash')>Gemini 2.5 Flash (Recommended - Fastest & High Accuracy)</option>
                                    <option value="gemini-2.5-pro" @selected($currentModel === 'gemini-2.5-pro')>Gemini 2.5 Pro (Advanced Reasoning)</option>
                                    <option value="gemini-flash-latest" @selected($currentModel === 'gemini-flash-latest')>Gemini Flash Latest</option>
                                    <option value="gemini-3.5-flash" @selected($currentModel === 'gemini-3.5-flash')>Gemini 3.5 Flash</option>
                                </select>
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    Select the active AI model endpoint for auto blog generation, landing pages, SEO recommendations, and live chat assistant.
                                </span>
                            </div>
                            
                            <div class="form-group" style="grid-column: span 2; margin-top: 1rem;">
                                <label for="google_my_business_link">Google My Business Reviews Link</label>
                                <input type="text" id="google_my_business_link" name="google_my_business_link" class="form-control" value="{{ $site['google_my_business_link'] ?? '' }}" placeholder="Paste your Google My Business reviews link here...">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    This link will be used to sync/generate authentic testimonials using AI.
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="gmb_location_id">Google Business Profile Location ID (Optional)</label>
                                <input type="text" id="gmb_location_id" name="gmb_location_id" class="form-control" value="{{ $site['gmb_location_id'] ?? '' }}" placeholder="e.g. locations/1234567890">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    Required for direct API publishing. Format: <code>locations/YOUR_LOCATION_ID</code>
                                </span>
                            </div>

                            <div class="form-group">
                                <label for="gmb_access_token">Google Business Profile Access Token (Optional)</label>
                                <input type="password" id="gmb_access_token" name="gmb_access_token" class="form-control" value="{{ $site['gmb_access_token'] ?? '' }}" placeholder="Paste your OAuth access token here...">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    OAuth 2.0 Bearer Access Token with <code>https://www.googleapis.com/auth/business.manage</code> scope.
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Top Navigation Manager Card -->
                    <div class="admin-card">
                        <h2>Top Navigation Manager</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">Customize the announcement bar, header tagline, location indicators, and helpline numbers.</p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="clinic_timings">Operational Timings</label>
                                <input type="text" id="clinic_timings" name="clinic_timings" class="form-control" value="{{ $site['clinic_timings'] ?? 'Mon - Sun: 8:00 AM - 9:00 PM' }}">
                            </div>
                            <div class="form-group">
                                <label for="clinic_locations_short">Header Short Locations Text</label>
                                <input type="text" id="clinic_locations_short" name="clinic_locations_short" class="form-control" value="{{ $site['clinic_locations_short'] ?? 'Sector 49 & Sector 83, Gurgaon' }}">
                            </div>
                            <div class="form-group">
                                <label for="phone_display">Display Helpline Number</label>
                                <input type="text" id="phone_display" name="phone_display" class="form-control" value="{{ $site['phone_display'] ?? '+91 95824 66445' }}">
                            </div>
                            <div class="form-group">
                                <label for="site_tagline">Logo Sub-Tagline</label>
                                <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="{{ $site['site_tagline'] ?? 'PREMIUM LAUNDRY & DRY CLEANING' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Footer Content Manager Card -->
                    <div class="admin-card">
                        <h2>Footer Content & Links Manager</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">Manage footer description, hub addresses, and copyright line.</p>
                        <div class="form-group" style="margin-bottom: 1.25rem;">
                            <label for="footer_description">Footer About Description</label>
                            <textarea id="footer_description" name="footer_description" class="form-control" rows="3">{{ $site['footer_description'] ?? 'Tenderfold is Gurgaon\'s trusted premium laundry, dry cleaning & specialized fabric care service. Doorstep pickup across all major sectors.' }}</textarea>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="address_baner">Sector 49 Hub Address</label>
                                <input type="text" id="address_baner" name="address_baner" class="form-control" value="{{ $site['address_baner'] ?? 'Shop No. 19, 1st Floor, B Block Shopping Plaza, Orchid Petals, South City II, Sector 49, Gurgaon - 122018' }}">
                            </div>
                            <div class="form-group">
                                <label for="address_hinjewadi">Sector 83 Hub Address</label>
                                <input type="text" id="address_hinjewadi" name="address_hinjewadi" class="form-control" value="{{ $site['address_hinjewadi'] ?? 'F-193, First Floor, Sapphire 83 Mall, Sector 83, Gurgaon - 122004' }}">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="copyright_text">Footer Copyright Line</label>
                                <input type="text" id="copyright_text" name="copyright_text" class="form-control" value="{{ $site['copyright_text'] ?? ('© ' . date('Y') . ' Tenderfold. Freshness in Every Single Fold. All Rights Reserved.') }}">
                            </div>
                            <div class="form-group" style="grid-column: span 2; margin-top: 0.5rem; padding-top: 1rem; border-top: 1px solid var(--admin-border);">
                                <label for="footer_helplines" style="font-weight: 600; display: flex; align-items: center; gap: 6px;">
                                    <i data-lucide="phone-call" style="width: 16px; height: 16px; color: var(--brand-blue);"></i> Footer Helpline Numbers
                                </label>
                                <textarea id="footer_helplines" name="footer_helplines" class="form-control" rows="2" placeholder="+91 95824 66445">{{ $site['footer_helplines'] ?? '+91 95824 66445' }}</textarea>
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    Enter helpline numbers (comma or new-line separated) to show in the website footer and Contact Us page helpline sections.
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="helpline_baner">Sector 49 Hub Helpline(s)</label>
                                <input type="text" id="helpline_baner" name="helpline_baner" class="form-control" value="{{ $site['helpline_baner'] ?? '+91 95824 66445' }}">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 4px;">Displayed under Sector 49 branch card.</span>
                            </div>
                            <div class="form-group">
                                <label for="helpline_hinjewadi">Sector 83 Hub Helpline(s)</label>
                                <input type="text" id="helpline_hinjewadi" name="helpline_hinjewadi" class="form-control" value="{{ $site['helpline_hinjewadi'] ?? '+91 95824 66445' }}">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 4px;">Displayed under Sector 83 branch card.</span>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Cloudflare Web Analytics Settings</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Enable Cloudflare Web Analytics to monitor pageviews, requests, and bandwidth inside this dashboard.
                        </p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="cloudflare_analytics_token">Cloudflare Web Analytics Token</label>
                                <input type="text" id="cloudflare_analytics_token" name="cloudflare_analytics_token" class="form-control" value="{{ $site['cloudflare_analytics_token'] ?? '' }}" placeholder="e.g. 66a2e4fb8...">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    Get the JS beacon token from the <a href="https://developers.cloudflare.com/analytics/web-analytics/" target="_blank" style="color: var(--mint-green); text-decoration: underline;">Cloudflare Web Analytics Guide</a>.
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="cloudflare_zone_id">Cloudflare Zone ID</label>
                                <input type="text" id="cloudflare_zone_id" name="cloudflare_zone_id" class="form-control" value="{{ $site['cloudflare_zone_id'] ?? '' }}" placeholder="e.g., d3b07384...">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="cloudflare_api_token">Cloudflare API Token (Analytics Read)</label>
                                <input type="password" id="cloudflare_api_token" name="cloudflare_api_token" class="form-control" value="{{ $site['cloudflare_api_token'] ?? '' }}" placeholder="Paste API Token here...">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    Generate a token with <strong>Zone.Analytics Read</strong> permission from your <a href="https://dash.cloudflare.com/profile/api-tokens" target="_blank" style="color: var(--mint-green); text-decoration: underline;">Cloudflare API Tokens settings</a>.
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Cloudflare R2 Object Storage</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Replace FTP with Cloudflare R2 S3-compatible storage. Use Cloudflare's 10 GB free tier for media files.
                        </p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="cloudflare_r2_account_id">Cloudflare Account ID</label>
                                <input type="text" id="cloudflare_r2_account_id" name="cloudflare_r2_account_id" class="form-control" value="{{ $site['cloudflare_r2_account_id'] ?? '' }}" placeholder="Account ID from Cloudflare dashboard">
                            </div>
                            <div class="form-group">
                                <label for="cloudflare_r2_bucket_name">Cloudflare R2 Bucket Name</label>
                                <input type="text" id="cloudflare_r2_bucket_name" name="cloudflare_r2_bucket_name" class="form-control" value="{{ $site['cloudflare_r2_bucket_name'] ?? '' }}" placeholder="e.g., mlg-finedge-assets">
                            </div>
                            <div class="form-group">
                                <label for="cloudflare_r2_access_key_id">R2 Access Key ID</label>
                                <input type="text" id="cloudflare_r2_access_key_id" name="cloudflare_r2_access_key_id" class="form-control" value="{{ $site['cloudflare_r2_access_key_id'] ?? '' }}" placeholder="Access Key ID">
                            </div>
                            <div class="form-group">
                                <label for="cloudflare_r2_secret_access_key">R2 Secret Access Key</label>
                                <input type="password" id="cloudflare_r2_secret_access_key" name="cloudflare_r2_secret_access_key" class="form-control" value="{{ $site['cloudflare_r2_secret_access_key'] ?? '' }}" placeholder="Secret Access Key">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="cloudflare_r2_public_url">R2 Public Custom Domain URL</label>
                                <input type="text" id="cloudflare_r2_public_url" name="cloudflare_r2_public_url" class="form-control" value="{{ $site['cloudflare_r2_public_url'] ?? '' }}" placeholder="e.g., https://assets.tenderfold.in">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    Create R2 credentials & connect a custom domain by following the <a href="https://developers.cloudflare.com/r2/" target="_blank" style="color: var(--mint-green); text-decoration: underline;">Cloudflare R2 Storage Guide</a>.
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card" style="border: 1px solid var(--accent-orange); position: relative; background: rgba(232, 92, 36, 0.01);">
                        <h2 style="color: var(--accent-orange); display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="database" style="width: 22px; height: 22px;"></i> Database Sync (Local to Live Server)
                        </h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Push and synchronize all local database content (Settings, Slides, Testimonials, Blogs, Services, and Page Contents) to your live production server database.
                        </p>
                        <div class="form-grid">
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="live_site_url">Live Site Base URL</label>
                                <input type="text" id="live_site_url" name="live_site_url" class="form-control" value="{{ $site['live_site_url'] ?? 'https://tenderfold.in' }}" placeholder="e.g., https://tenderfold.in">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    This is the target live domain where data will be pushed. The Sync uses your configured <strong>R2 Secret Access Key</strong> as a secure verification token between your local and live servers.
                                </span>
                            </div>
                        </div>
                        <div style="margin-top: 1.5rem;">
                            <button type="button" class="btn-submit" style="background: var(--accent-orange) !important; border-color: var(--accent-orange); display: inline-flex; align-items: center; gap: 8px; width: auto; font-size: 0.9rem;" onclick="pushLocalDatabaseToLive(this)">
                                <i data-lucide="cloud-lightning" style="width: 16px; height: 16px;"></i> Push Local Database to Live Server
                            </button>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>SMTP Mailer Settings (Lead Notification Email)</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Configure SMTP details to send automated email alerts to admin when a new inquiry is submitted.
                        </p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="smtp_host">SMTP Host</label>
                                <input type="text" id="smtp_host" name="smtp_host" class="form-control" value="{{ $site['smtp_host'] ?? '' }}" placeholder="smtp.mailtrap.io">
                            </div>
                            <div class="form-group">
                                <label for="smtp_port">SMTP Port</label>
                                <input type="text" id="smtp_port" name="smtp_port" class="form-control" value="{{ $site['smtp_port'] ?? '587' }}" placeholder="587">
                            </div>
                            <div class="form-group">
                                <label for="smtp_username">SMTP Username</label>
                                <input type="text" id="smtp_username" name="smtp_username" class="form-control" value="{{ $site['smtp_username'] ?? '' }}" placeholder="username">
                            </div>
                            <div class="form-group">
                                <label for="smtp_password">SMTP Password</label>
                                <input type="password" id="smtp_password" name="smtp_password" class="form-control" value="{{ $site['smtp_password'] ?? '' }}" placeholder="password">
                            </div>
                            <div class="form-group">
                                <label for="smtp_encryption">SMTP Encryption</label>
                                <select id="smtp_encryption" name="smtp_encryption" class="form-control">
                                    <option value="tls" @selected(($site['smtp_encryption'] ?? 'tls') == 'tls')>TLS</option>
                                    <option value="ssl" @selected(($site['smtp_encryption'] ?? 'tls') == 'ssl')>SSL</option>
                                    <option value="none" @selected(($site['smtp_encryption'] ?? 'tls') == 'none')>None</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="smtp_from_address">From Email Address</label>
                                <input type="email" id="smtp_from_address" name="smtp_from_address" class="form-control" value="{{ $site['smtp_from_address'] ?? 'info@tenderfold.in' }}" placeholder="info@tenderfold.in">
                            </div>
                            <div class="form-group">
                                <label for="smtp_from_name">From Sender Name</label>
                                <input type="text" id="smtp_from_name" name="smtp_from_name" class="form-control" value="{{ $site['smtp_from_name'] ?? 'Tenderfold Alerts' }}" placeholder="Tenderfold Alerts">
                            </div>
                            <div class="form-group">
                                <label for="smtp_to_email">Recipient Email Address (Admin Alert)</label>
                                <input type="email" id="smtp_to_email" name="smtp_to_email" class="form-control" value="{{ $site['smtp_to_email'] ?? 'admin@tenderfold.in' }}" placeholder="admin@tenderfold.in">
                            </div>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>WhatsApp API Settings (Lead Notification WhatsApp)</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Configure WhatsApp API settings to send automated alerts when a new inquiry is submitted.
                        </p>
                        <div class="form-grid">
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="whatsapp_api_url">WhatsApp API Gateway URL</label>
                                <input type="text" id="whatsapp_api_url" name="whatsapp_api_url" class="form-control" value="{{ $site['whatsapp_api_url'] ?? '' }}" placeholder="https://api.whatsapp.com/send or custom API endpoint">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="whatsapp_api_token">WhatsApp API Token / Key</label>
                                <input type="password" id="whatsapp_api_token" name="whatsapp_api_token" class="form-control" value="{{ $site['whatsapp_api_token'] ?? '' }}" placeholder="API Token / Auth Key">
                            </div>
                            <div class="form-group">
                                <label for="whatsapp_admin_recipient">Admin Recipient WhatsApp Number (with country code, no +)</label>
                                <input type="text" id="whatsapp_admin_recipient" name="whatsapp_admin_recipient" class="form-control" value="{{ $site['whatsapp_admin_recipient'] ?? '919672777749' }}" placeholder="e.g. 919672777749">
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Save Parameters</button>
                </form>
            </div>

            <!-- LEADS MANAGER TAB -->
            <div class="tab-panel @if($activeTab === 'leads') active @endif" id="tab-leads">
                <!-- Stats Overview Grid -->
                <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2rem;">
                    <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0;">
                        <h4 style="margin: 0; font-size: 0.85rem; color: var(--admin-text-muted); text-transform: uppercase;">Total Leads</h4>
                        <p style="font-size: 2.25rem; font-weight: 800; color: var(--primary-teal-dark); margin: 0.5rem 0 0 0;">{{ \App\Models\Lead::count() }}</p>
                    </div>
                    <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-left: 4px solid #3b82f6;">
                        <h4 style="margin: 0; font-size: 0.85rem; color: var(--admin-text-muted); text-transform: uppercase;">New</h4>
                        <p style="font-size: 2.25rem; font-weight: 800; color: #3b82f6; margin: 0.5rem 0 0 0;">{{ \App\Models\Lead::where('status', 'New')->count() }}</p>
                    </div>
                    <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-left: 4px solid #eab308;">
                        <h4 style="margin: 0; font-size: 0.85rem; color: var(--admin-text-muted); text-transform: uppercase;">Contacted</h4>
                        <p style="font-size: 2.25rem; font-weight: 800; color: #eab308; margin: 0.5rem 0 0 0;">{{ \App\Models\Lead::where('status', 'Contacted')->count() }}</p>
                    </div>
                    <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-left: 4px solid #f97316;">
                        <h4 style="margin: 0; font-size: 0.85rem; color: var(--admin-text-muted); text-transform: uppercase;">In Progress</h4>
                        <p style="font-size: 2.25rem; font-weight: 800; color: #f97316; margin: 0.5rem 0 0 0;">{{ \App\Models\Lead::where('status', 'In Progress')->count() }}</p>
                    </div>
                    <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-left: 4px solid #22c55e;">
                        <h4 style="margin: 0; font-size: 0.85rem; color: var(--admin-text-muted); text-transform: uppercase;">Closed</h4>
                        <p style="font-size: 2.25rem; font-weight: 800; color: #22c55e; margin: 0.5rem 0 0 0;">{{ \App\Models\Lead::where('status', 'Closed')->count() }}</p>
                    </div>
                </div>

                <!-- Leads Table Card -->
                <div class="admin-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <h2>Lead Records</h2>
                            <span style="font-size: 0.85rem; color: var(--admin-text-muted);">Manage your customer queries and requests</span>
                        </div>
                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <a href="{{ route('admin.leads.export') }}" class="btn" style="background-color: var(--primary-teal); color: white; display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; font-weight: 600; font-size: 0.9rem;">
                                <i data-lucide="download" style="width: 16px; height: 16px; vertical-align: middle;"></i> Export CSV Sheet
                            </a>
                            <button type="button" onclick="submitBulkDelete()" id="btn-bulk-delete" class="btn" style="background-color: #ef4444; color: white; display: none; align-items: center; gap: 0.5rem; padding: 0.5rem 1rem; border: none; border-radius: 4px; font-weight: 600; font-size: 0.9rem; cursor: pointer;">
                                <i data-lucide="trash-2" style="width: 16px; height: 16px; vertical-align: middle;"></i> Delete Selected (<span id="bulk-select-count">0</span>)
                            </button>
                        </div>
                    </div>

                    @if($leads->isEmpty())
                        <div style="text-align: center; padding: 3rem 1.5rem; color: var(--admin-text-muted);">
                            <i data-lucide="inbox" style="width: 48px; height: 48px; stroke-width: 1.5; margin-bottom: 1rem; color: #cbd5e1;"></i>
                            <p style="font-size: 1.1rem; margin-bottom: 0.25rem;">No leads found</p>
                            <p style="font-size: 0.85rem;">When clients submit a form on the website, they will appear here.</p>
                        </div>
                    @else
                        <!-- Standalone Bulk Delete Form -->
                        <form id="bulk-delete-form" action="{{ route('admin.leads.bulk-delete') }}" method="POST" style="display: none;">
                            @csrf
                            <div id="bulk-delete-inputs"></div>
                        </form>

                        <div class="admin-table-wrap">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th style="width: 40px; text-align: center;">
                                            <input type="checkbox" id="check-all-leads" onchange="toggleAllLeads(this)" style="cursor: pointer;">
                                        </th>
                                        <th>Date & Time</th>
                                        <th>Client Information</th>
                                        <th>Source</th>
                                        <th>Status</th>
                                        <th style="text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($leads as $lead)
                                        <!-- Main Info Row -->
                                        <tr id="lead-row-{{ $lead->id }}">
                                            <td style="text-align: center;">
                                                <input type="checkbox" value="{{ $lead->id }}" class="lead-checkbox" onchange="updateBulkDeleteState()" style="cursor: pointer;">
                                            </td>
                                            <td style="font-size: 0.85rem; color: var(--admin-text-muted); white-space: nowrap;">
                                                {{ $lead->created_at->format('d M Y, h:i A') }}<br>
                                                <small style="color: #94a3b8;">{{ $lead->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td>
                                                <strong style="font-size: 1.05rem; color: var(--primary-teal-dark); display: block;">{{ $lead->name }}</strong>
                                                <div style="display: flex; gap: 10px; margin-top: 0.25rem; font-size: 0.85rem;">
                                                    <a href="tel:{{ $lead->phone }}" style="color: var(--accent-orange); display: flex; align-items: center; gap: 4px; font-weight: 500;">
                                                        <i data-lucide="phone" style="width: 12px; height: 12px;"></i> {{ $lead->phone }}
                                                    </a>
                                                    @if($lead->email)
                                                        <a href="mailto:{{ $lead->email }}" style="color: var(--admin-text-muted); display: flex; align-items: center; gap: 4px;">
                                                            <i data-lucide="mail" style="width: 12px; height: 12px;"></i> {{ $lead->email }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge" style="background-color: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; font-size: 0.75rem;">
                                                    {{ $lead->source }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($lead->status === 'New')
                                                    <span class="badge" style="background-color: #dbeafe; color: #1e40af;">New</span>
                                                @elseif($lead->status === 'Contacted')
                                                    <span class="badge" style="background-color: #fef9c3; color: #854d0e;">Contacted</span>
                                                @elseif($lead->status === 'In Progress')
                                                    <span class="badge" style="background-color: #ffedd5; color: #9a3412;">In Progress</span>
                                                @elseif($lead->status === 'Closed')
                                                    <span class="badge" style="background-color: #dcfce7; color: #166534;">Closed</span>
                                                @endif
                                            </td>
                                            <td style="text-align: right; white-space: nowrap;">
                                                <button type="button" onclick="toggleLeadDetails({{ $lead->id }})" class="btn-action edit" title="View Details / Edit" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-weight: 500; font-size: 0.85rem; border: 1px solid var(--primary-teal); background-color: rgba(92, 203, 179, 0.15); color: var(--mint-green); cursor: pointer;">
                                                    <i data-lucide="edit-3" style="width: 14px; height: 14px;"></i> Details
                                                </button>
                                            </td>
                                        </tr>
                                        
                                        <!-- Inline Expandable Detail/Edit Form Row -->
                                        <tr id="lead-details-{{ $lead->id }}" class="lead-detail-row" style="display: none; background-color: var(--admin-dark-sidebar);">
                                            <td colspan="6" style="padding: 1.5rem; border-top: 1px dashed var(--admin-border); border-bottom: 1px dashed var(--admin-border);">
                                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                                    
                                                    <!-- Details Section -->
                                                    <div>
                                                        <h4 style="margin: 0 0 0.75rem 0; color: var(--mint-green); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 0;">Lead Information Details</h4>
                                                        <div style="background-color: rgba(255,255,255,0.02); border: 1px solid var(--admin-border); border-radius: var(--radius-md); padding: 1rem; font-size: 0.9rem; line-height: 1.5; color: rgba(255,255,255,0.95);">
                                                            {!! nl2br(e($lead->message)) !!}
                                                        </div>
                                                        
                                                        <div style="margin-top: 1rem;">
                                                            <h5 style="margin: 0 0 0.5rem 0; color: rgba(255,255,255,0.6); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Multiple Activity Tracking Log</h5>
                                                            @if($lead->activities && $lead->activities->count() > 0)
                                                                <div style="display: flex; flex-direction: column; gap: 8px; max-height: 200px; overflow-y: auto; background-color: rgba(0,0,0,0.3); padding: 10px; border-radius: 6px; border: 1px solid var(--admin-border);">
                                                                    @foreach($lead->activities as $act)
                                                                        <div style="font-size: 0.82rem; border-bottom: 1px dashed rgba(255,255,255,0.1); padding-bottom: 6px;">
                                                                            <strong style="color: var(--mint-green);">{{ $act->user_name }}</strong> 
                                                                            <span style="color: #94a3b8;">({{ $act->created_at->format('d M Y, h:i A') }}):</span> 
                                                                            <span style="color: #e2e8f0;">{{ $act->notes ?? 'Updated lead record' }}</span>
                                                                            @if($act->status_from || $act->status_to)
                                                                                <span class="badge" style="font-size: 0.7rem; padding: 2px 6px; background: #1e293b; color: #60a5fa; margin-left: 6px;">{{ $act->status_from }} → {{ $act->status_to }}</span>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @else
                                                                <p style="font-size: 0.85rem; color: #94a3b8; font-style: italic; margin: 0;">No activities logged yet.</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    
                                                    <!-- Actions & Notes Form Section -->
                                                    <div>
                                                        <h4 style="margin: 0 0 0.75rem 0; color: var(--mint-green); font-size: 0.95rem; text-transform: uppercase; letter-spacing: 0.5px; border: none; padding: 0;">Update Status & Actions</h4>
                                                        <form action="{{ route('admin.leads.update', $lead->id) }}" method="POST" style="margin-bottom: 0;">
                                                            @csrf
                                                            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                                                                <div class="form-group">
                                                                    <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 4px;">Lead Status</label>
                                                                    <select name="status" class="form-control" required style="padding: 0.5rem; height: auto; background-color: var(--admin-dark-bg); color: #ffffff;">
                                                                        <option value="New" @selected($lead->status === 'New') style="background-color: var(--admin-dark-bg); color: #ffffff;">New</option>
                                                                        <option value="Contacted" @selected($lead->status === 'Contacted') style="background-color: var(--admin-dark-bg); color: #ffffff;">Contacted</option>
                                                                        <option value="In Progress" @selected($lead->status === 'In Progress') style="background-color: var(--admin-dark-bg); color: #ffffff;">In Progress</option>
                                                                        <option value="Closed" @selected($lead->status === 'Closed') style="background-color: var(--admin-dark-bg); color: #ffffff;">Closed</option>
                                                                    </select>
                                                                </div>
                                                                
                                                                <div class="form-group">
                                                                    <label style="font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 4px;">Internal Follow-up Notes</label>
                                                                    <textarea name="notes" class="form-control" rows="3" placeholder="Write internal notes about client conversation..." style="font-size: 0.85rem; padding: 0.5rem; background-color: var(--admin-dark-bg); color: #ffffff;">{{ $lead->notes }}</textarea>
                                                                </div>
                                                            </div>
                                                            <button type="submit" class="btn-submit" style="padding: 0.5rem 1rem; width: auto; margin-top: 0.75rem; font-size: 0.85rem;">
                                                                Update Status & Notes
                                                            </button>
                                                        </form>
                                                        
                                                        <hr style="border: 0; border-top: 1px solid var(--admin-border); margin: 1.25rem 0;">
                                                        
                                                        <div style="display: flex; justify-content: space-between; align-items: center;">
                                                            <span style="font-size: 0.8rem; color: #94a3b8;">Dangerous operation:</span>
                                                            <form action="{{ route('admin.leads.delete', $lead->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete this lead record?');" style="margin: 0;">
                                                                @csrf
                                                                <button type="submit" class="btn-action delete" style="padding: 0.4rem 0.8rem; font-size: 0.8rem; display: flex; align-items: center; gap: 4px; height: auto; background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 4px; cursor: pointer;">
                                                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Delete Lead Record
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Custom Leads Pagination -->
                        <div class="admin-pagination" style="margin-top: 1.5rem; display: flex; justify-content: center; align-items: center; gap: 1rem;">
                            @if ($leads->onFirstPage())
                                <span class="btn-action" style="opacity: 0.5; cursor: not-allowed; padding: 0.5rem 1rem;"><i data-lucide="chevron-left"></i> Previous</span>
                            @else
                                <a href="{{ $leads->appends(['blogs_page' => request('blogs_page')])->previousPageUrl() }}#tab-leads" class="btn-action" style="padding: 0.5rem 1rem; color: #fff; background: var(--admin-input-bg); border-radius: var(--radius-md); text-decoration: none;"><i data-lucide="chevron-left"></i> Previous</a>
                            @endif

                            <span style="color: var(--admin-text-muted); font-size: 0.9rem;">Page {{ $leads->currentPage() }} of {{ $leads->lastPage() }}</span>

                            @if ($leads->hasMorePages())
                                <a href="{{ $leads->appends(['blogs_page' => request('blogs_page')])->nextPageUrl() }}#tab-leads" class="btn-action" style="padding: 0.5rem 1rem; color: #fff; background: var(--admin-input-bg); border-radius: var(--radius-md); text-decoration: none;">Next <i data-lucide="chevron-right"></i></a>
                            @else
                                <span class="btn-action" style="opacity: 0.5; cursor: not-allowed; padding: 0.5rem 1rem;">Next <i data-lucide="chevron-right"></i></span>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- LIVE CHAT MANAGER TAB -->
            <div class="tab-panel @if($activeTab === 'livechat') active @endif" id="tab-livechat">
                <div class="admin-card" style="padding: 0; overflow: hidden; display: flex; height: calc(100vh - 160px); min-height: 500px; border: 1px solid var(--admin-border); background-color: var(--admin-dark-sidebar); border-radius: var(--radius-lg);">
                    
                    <!-- Chat Sessions List (Sidebar) -->
                    <div style="width: 320px; border-right: 1px solid var(--admin-border); display: flex; flex-direction: column; background-color: var(--admin-dark-sidebar);">
                        <div style="padding: 1.25rem; border-bottom: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <h2 style="margin: 0; font-size: 1.2rem;">Live Support Chats</h2>
                                <p style="margin: 4px 0 0 0; font-size: 0.8rem; color: var(--admin-text-muted);">Real-time patient chat sessions</p>
                            </div>
                            <button type="button" onclick="loadAdminChatSessions(); if(typeof showToast === 'function') showToast('Chat list refreshed!', 'success');" style="padding: 6px; background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.1); border-radius: 4px; color: var(--mint-green); cursor: pointer; display: flex; align-items: center; justify-content: center; height: auto;" title="Refresh Chat List">
                                <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i>
                            </button>
                        </div>
                        <div id="admin-chat-sessions-list" style="flex-grow: 1; overflow-y: auto; display: flex; flex-direction: column;">
                            <!-- Active sessions loaded dynamically -->
                            <div style="padding: 2rem; text-align: center; color: var(--admin-text-muted); font-size: 0.9rem;">
                                Loading chat sessions...
                            </div>
                        </div>
                    </div>

                    <!-- Conversation Window (Main) -->
                    <div style="flex-grow: 1; display: flex; flex-direction: column; background-color: var(--admin-dark-bg);">
                        <!-- Conversation Header -->
                        <div id="admin-chat-header" style="padding: 1.1rem 1.5rem; border-bottom: 1px solid var(--admin-border); display: flex; justify-content: space-between; align-items: center; background-color: var(--admin-dark-sidebar);">
                            <div>
                                <h3 id="admin-chat-header-title" style="margin: 0; font-size: 1.05rem; color: var(--mint-green);">Select a Chat</h3>
                                <p id="admin-chat-header-subtitle" style="margin: 2px 0 0 0; font-size: 0.8rem; color: var(--admin-text-muted);">Please pick a patient chat session from the list to start responding.</p>
                            </div>
                            <div style="display: flex; gap: 8px; align-items: center;">
                                <button type="button" id="admin-chat-refresh-btn" onclick="loadAdminChatMessages(); if(typeof showToast === 'function') showToast('Conversation refreshed!', 'success');" style="display: none; align-items: center; gap: 4px; padding: 6px 12px; font-size: 0.8rem; height: auto; background-color: rgba(92, 203, 179, 0.1); color: var(--mint-green); border: 1px solid rgba(92, 203, 179, 0.2); border-radius: 4px; cursor: pointer;">
                                    <i data-lucide="refresh-cw" style="width: 14px; height: 14px;"></i> Refresh Messages
                                </button>
                                <button type="button" id="admin-chat-delete-btn" onclick="deleteCurrentChat()" class="btn-action delete" style="display: none; align-items: center; gap: 4px; padding: 6px 12px; font-size: 0.8rem; height: auto; background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 4px; cursor: pointer;">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Close & Delete Chat
                                </button>
                            </div>
                        </div>


                        <!-- Messages Content area -->
                        <div id="admin-chat-messages-wrap" style="flex-grow: 1; padding: 1.5rem; overflow-y: auto; display: flex; flex-direction: column; gap: 12px;">
                            <div style="margin: auto; text-align: center; color: var(--admin-text-muted);">
                                <i data-lucide="message-square" style="width: 48px; height: 48px; stroke-width: 1.25; margin-bottom: 0.75rem; color: var(--admin-border);"></i>
                                <p style="font-size: 0.95rem; margin: 0;">No Chat Selected</p>
                            </div>
                        </div>

                        <!-- Chat Reply Input area -->
                        <div id="admin-chat-input-wrap" style="padding: 1rem 1.5rem; border-top: 1px solid var(--admin-border); background-color: var(--admin-dark-sidebar); display: none; gap: 10px;">
                            <input type="text" id="admin-chat-reply-input" placeholder="Type your reply here..." onkeydown="handleAdminChatKey(event)" style="flex-grow: 1; background-color: var(--admin-input-bg); border: 1px solid var(--admin-border); color: white; padding: 0.75rem 1rem; border-radius: 24px; outline: none; font-size: 0.9rem;">
                            <button type="button" onclick="sendAdminReply()" class="btn" style="background-color: var(--primary-teal); color: white; border: none; padding: 0 1.25rem; border-radius: 24px; font-weight: 600; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 6px;">
                                Send <i data-lucide="send" style="width: 14px; height: 14px;"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- Live Chat Auto-Reply & AI Receptionist Settings Card -->
                <div class="admin-card" style="margin-top: 2rem;">
                    <h2><i data-lucide="bot" style="width: 20px; height: 20px; vertical-align: middle; margin-right: 6px; color: var(--mint-green);"></i> Live Chat Auto-Reply & AI Receptionist Settings</h2>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                        Customize automatic greetings, enable or disable AI auto-replies, and provide instructions for Gemini AI receptionist.
                    </p>
                    
                    <form action="{{ route('admin.parameters.update') }}" method="POST">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="chat_ai_enabled" style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="hidden" name="chat_ai_enabled" value="0">
                                    <input type="checkbox" id="chat_ai_enabled" name="chat_ai_enabled" value="1" @checked(($site['chat_ai_enabled'] ?? '1') == '1') style="width: 18px; height: 18px; cursor: pointer;">
                                    <strong>Enable Gemini AI Assistant Auto-Replies for New Visitor Queries</strong>
                                </label>
                            </div>
                            
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="chat_welcome_message">Welcome Auto-Greeting Message</label>
                                <input type="text" id="chat_welcome_message" name="chat_welcome_message" class="form-control" value="{{ $site['chat_welcome_message'] ?? 'Hello! Welcome to Tenderfold. How can we help you today with your laundry, dry cleaning, or pickup?' }}" placeholder="e.g. Hello! Welcome to Tenderfold...">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 4px;">Sent automatically as the initial greeting when a customer opens live chat.</span>
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="chat_ai_custom_prompt">Custom AI Receptionist System Prompt / Instructions</label>
                                <textarea id="chat_ai_custom_prompt" name="chat_ai_custom_prompt" class="form-control" rows="4" placeholder="e.g. Tenderfold is open Mon-Sun 8am-9pm. Free pickup & delivery across Gurgaon. Premium wash & fold, dry cleaning, steam ironing, shoe spa.">{{ $site['chat_ai_custom_prompt'] ?? '' }}</textarea>
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 4px;">Add specific laundry pricing, timings, or pickup guidelines to instruct Gemini AI.</span>
                            </div>

                            <div class="form-group" style="grid-column: span 2;">
                                <label for="chat_fallback_message">Offline / Human Assistance Fallback Message</label>
                                <input type="text" id="chat_fallback_message" name="chat_fallback_message" class="form-control" value="{{ $site['chat_fallback_message'] ?? 'Thank you for your message! Our team will connect with you shortly. You can also schedule a pickup directly or call us at +91 95824 66445.' }}" placeholder="e.g. Thank you for your message...">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 4px;">Sent when AI is turned OFF or when Gemini API is unavailable.</span>
                            </div>
                        </div>

                        <div style="margin-top: 1.25rem;">
                            <button type="submit" class="btn-submit"><i data-lucide="save" style="width: 16px; height: 16px; vertical-align: middle; margin-right: 6px;"></i> Save Chat Settings</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- 2. BRANDING & LOGOS TAB -->
            <div class="tab-panel @if($activeTab === 'branding') active @endif" id="tab-branding">
                <form action="{{ route('admin.parameters.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="admin-card">
                        <h2>Website Information</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="site_name">Website Name</label>
                                <input type="text" id="site_name" name="site_name" class="form-control" value="{{ $site['site_name'] ?? '' }}" required>
                            </div>
                            <div class="form-group">
                                <label for="site_tagline">Website Tagline</label>
                                <input type="text" id="site_tagline" name="site_tagline" class="form-control" value="{{ $site['site_tagline'] ?? '' }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Logo & Favicon Customization</h2>
                        <p style="font-size:0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Uploaded images are automatically converted to <strong>WebP format</strong> on the server for web speed optimizations. SVG format files will be uploaded untouched.
                        </p>
                        
                        <div class="form-grid">
                            <!-- Logo file -->
                            <div class="form-group">
                                <label>Header & Footer Logo (Upload File)</label>
                                <input type="file" name="logo" class="form-control" accept="image/*">
                                <div style="margin-top: 10px;">
                                    <label>Or Paste Logo URL</label>
                                    <input type="text" name="logo_url" class="form-control" value="{{ !empty($site['logo_path']) && (str_starts_with($site['logo_path'], 'http') || str_starts_with($site['logo_path'], '//')) ? $site['logo_path'] : '' }}" placeholder="https://example.com/logo.webp">
                                </div>
                                <div class="image-preview-container">
                                    <div class="image-preview">
                                        @if(!empty($site['logo_path']))
                                            <img src="{{ site_image($site['logo_path']) }}" alt="Logo">
                                        @else
                                            <span style="font-size:0.7rem; color: var(--admin-text-muted);">Default SVG</span>
                                        @endif
                                    </div>
                                    @if(!empty($site['logo_path']) && !str_starts_with($site['logo_path'], 'http') && !str_starts_with($site['logo_path'], '//'))
                                        <span class="badge-webp">WebP Optimized</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Favicon file -->
                            <div class="form-group">
                                <label>Browser Favicon (Upload File)</label>
                                <input type="file" name="favicon" class="form-control" accept="image/*">
                                <div style="margin-top: 10px;">
                                    <label>Or Paste Favicon URL</label>
                                    <input type="text" name="favicon_url" class="form-control" value="{{ !empty($site['favicon_path']) && (str_starts_with($site['favicon_path'], 'http') || str_starts_with($site['favicon_path'], '//')) ? $site['favicon_path'] : '' }}" placeholder="https://example.com/favicon.ico">
                                </div>
                                <div class="image-preview-container">
                                    <div class="image-preview">
                                        @if(!empty($site['favicon_path']))
                                            <img src="{{ site_image($site['favicon_path']) }}" alt="Favicon">
                                        @else
                                            <span style="font-size:0.7rem; color: var(--admin-text-muted);">Default ICO</span>
                                        @endif
                                    </div>
                                    @if(!empty($site['favicon_path']) && !str_starts_with($site['favicon_path'], 'http') && !str_starts_with($site['favicon_path'], '//'))
                                        <span class="badge-webp">WebP Optimized</span>
                                    @endif
                                </div>
                            </div>
                    </div>
                </div>

                    <div class="admin-card" style="margin-top: 1.5rem;">
                        <h2>Live Chat Widget Customization</h2>
                        <div class="form-grid">
                            <!-- Chat Assistant Name -->
                            <div class="form-group">
                                <label for="chat_assistant_name">Chat Assistant Display Name</label>
                                <input type="text" id="chat_assistant_name" name="chat_assistant_name" class="form-control" value="{{ $site['chat_assistant_name'] ?? 'Tenderfold Assistant' }}" required>
                            </div>
                            
                            <!-- Chat Assistant Avatar File -->
                            <div class="form-group">
                                <label>Chat Assistant Avatar (Upload File)</label>
                                <input type="file" name="chat_assistant_avatar" class="form-control" accept="image/*">
                                <div style="margin-top: 10px;">
                                    <label>Or Paste Avatar Image URL</label>
                                    <input type="text" name="chat_assistant_avatar_url" class="form-control" value="{{ !empty($site['chat_assistant_avatar']) && (str_starts_with($site['chat_assistant_avatar'], 'http') || str_starts_with($site['chat_assistant_avatar'], '//')) ? $site['chat_assistant_avatar'] : '' }}" placeholder="https://example.com/avatar.webp">
                                </div>
                                <div class="image-preview-container" style="margin-top: 15px; display: flex; align-items: center; gap: 12px;">
                                    <div class="image-preview" style="border-radius: 50%; overflow: hidden; width: 60px; height: 60px; border: 2px solid var(--admin-border); display: flex; align-items: center; justify-content: center;">
                                        @if(!empty($site['chat_assistant_avatar']))
                                            <img src="{{ site_image($site['chat_assistant_avatar']) }}" alt="Avatar" style="width: 100%; height: 100%; object-fit: cover;">
                                        @else
                                            <div style="background-color: var(--secondary-teal); color: white; display: flex; align-items: center; justify-content: center; width: 100%; height: 100%; font-size: 0.95rem; font-weight: bold; font-family: 'Outfit';">
                                                TA
                                            </div>
                                        @endif
                                    </div>
                                    @if(!empty($site['chat_assistant_avatar']) && !str_starts_with($site['chat_assistant_avatar'], 'http') && !str_starts_with($site['chat_assistant_avatar'], '//'))
                                        <span class="badge-webp">WebP Optimized</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Save Branding</button>
                </form>
            </div>

            <!-- 3. HOMEPAGE SLIDER TAB -->
            <div class="tab-panel @if($activeTab === 'slider') active @endif" id="tab-slider">
                <!-- Add / Edit Slide Card -->
                <div class="admin-card" id="slide-form-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <h2 style="margin: 0;" id="slide-form-title">Add New Homepage Hero Slide</h2>
                            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 4px 0 0;">Manage your hero background images (desktop & mobile), title, description, buttons, and trust strip statistics.</p>
                        </div>
                        <button type="button" class="btn-ai-sparkle" onclick="openAiModal('slider')" style="display: inline-flex; align-items: center; gap: 6px;">
                            <i data-lucide="sparkles" style="width: 16px; height: 16px;"></i> Generate Slide via AI
                        </button>
                    </div>
                    <form id="slide-form" action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="slide_id" value="">
                        
                        <!-- Core Text Details -->
                        <div class="form-grid">
                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="slide_eyebrow">Eyebrow Badge Text</label>
                                <input type="text" id="slide_eyebrow" name="eyebrow" class="form-control" placeholder="e.g. Premium Laundry & Dry Cleaner in Gurgaon">
                            </div>
                            <div class="form-group">
                                <label for="slide_title">Slide Main Title <span style="color:#ef4444;">*</span> <small style="color:var(--admin-text-muted); font-weight:normal;">(supports &lt;span class="highlight-blue"&gt;)</small></label>
                                <input type="text" id="slide_title" name="title" class="form-control" required placeholder="e.g. Freshness in Every Single Fold">
                            </div>
                            <div class="form-group">
                                <label for="slide_subtitle">Slide Subtitle / Description</label>
                                <textarea id="slide_subtitle" name="subtitle" class="form-control" rows="2" placeholder="e.g. Gurgaon's trusted premium laundry, dry cleaning & specialized fabric spa. Doorstep pickup across all major sectors."></textarea>
                            </div>
                        </div>

                        <!-- CTA Buttons Configuration -->
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--admin-border); border-radius: 10px; padding: 1rem; margin-top: 1rem;">
                            <h4 style="font-size: 0.9rem; margin: 0 0 0.75rem; color: var(--admin-text-main); display: flex; align-items: center; gap: 6px;">
                                <i data-lucide="mouse-pointer-click" style="width: 15px; height: 15px; color: #38bdf8;"></i> Call-to-Action Buttons
                            </h4>
                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="slide_btn_text">Primary Button Text</label>
                                    <input type="text" id="slide_btn_text" name="button_text" class="form-control" placeholder="Book Free Pickup">
                                </div>
                                <div class="form-group">
                                    <label for="slide_btn_link">Primary Button Link</label>
                                    <input type="text" id="slide_btn_link" name="button_link" class="form-control" placeholder="#book">
                                </div>
                                <div class="form-group">
                                    <label for="slide_btn_2_text">Secondary Button Text</label>
                                    <input type="text" id="slide_btn_2_text" name="button_2_text" class="form-control" placeholder="See Our Prices">
                                </div>
                                <div class="form-group">
                                    <label for="slide_btn_2_link">Secondary Button Link</label>
                                    <input type="text" id="slide_btn_2_link" name="button_2_link" class="form-control" placeholder="/services">
                                </div>
                            </div>
                        </div>

                        <!-- Trust Statistics Strip Configuration -->
                        <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--admin-border); border-radius: 10px; padding: 1rem; margin-top: 1rem;">
                            <h4 style="font-size: 0.9rem; margin: 0 0 0.75rem; color: var(--admin-text-main); display: flex; align-items: center; gap: 6px;">
                                <i data-lucide="award" style="width: 15px; height: 15px; color: var(--mint-green);"></i> Trust Strip Statistics (Bottom Badges)
                            </h4>
                            <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Stat 1 (Number / Label)</label>
                                    <input type="text" id="slide_trust_1_num" name="trust_1_num" class="form-control" placeholder="50,000+" style="margin-bottom: 6px;">
                                    <input type="text" id="slide_trust_1_label" name="trust_1_label" class="form-control" placeholder="Garments Delivered">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Stat 2 (Number / Label)</label>
                                    <input type="text" id="slide_trust_2_num" name="trust_2_num" class="form-control" placeholder="100%" style="margin-bottom: 6px;">
                                    <input type="text" id="slide_trust_2_label" name="trust_2_label" class="form-control" placeholder="Eco-Friendly Solvents">
                                </div>
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label>Stat 3 (Number / Label)</label>
                                    <input type="text" id="slide_trust_3_num" name="trust_3_num" class="form-control" placeholder="Sector 49 & 83" style="margin-bottom: 6px;">
                                    <input type="text" id="slide_trust_3_label" name="trust_3_label" class="form-control" placeholder="Gurgaon Hubs">
                                </div>
                            </div>
                        </div>

                        <!-- Responsive Background Images Section -->
                        <div style="background: rgba(255,255,255,0.03); border: 1px solid var(--admin-border); border-radius: 12px; padding: 1.25rem; margin-top: 1rem; margin-bottom: 1rem;">
                            <h3 style="font-size: 1rem; margin-top: 0; margin-bottom: 0.75rem; color: var(--admin-text-main); display: flex; align-items: center; gap: 8px;">
                                <i data-lucide="image" style="width: 18px; height: 18px; color: var(--mint-green);"></i> Slide Background Images (Desktop & Mobile)
                            </h3>
                            <div class="form-grid">
                                <!-- Desktop Image -->
                                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px dashed var(--admin-border);">
                                    <label for="slide_image" style="display: flex; align-items: center; gap: 6px; font-weight: 600; color: #38bdf8;">
                                        <i data-lucide="monitor" style="width: 15px; height: 15px;"></i> Desktop Background Image
                                    </label>
                                    <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-bottom: 6px;">Recommended landscape aspect ratio (16:9, ~1920x900px)</span>
                                    <input type="file" id="slide_image" name="image" class="form-control" accept="image/*">
                                    <div style="margin-top: 8px;">
                                        <label for="slide_image_url" style="font-size: 0.8rem;">Or Desktop Image URL</label>
                                        <input type="text" id="slide_image_url" name="image_url" class="form-control" placeholder="https://pub-...r2.dev/desktop-hero.webp">
                                    </div>
                                </div>

                                <!-- Mobile Image -->
                                <div class="form-group" style="background: rgba(255,255,255,0.02); padding: 12px; border-radius: 8px; border: 1px dashed var(--admin-border);">
                                    <label for="slide_mobile_image" style="display: flex; align-items: center; gap: 6px; font-weight: 600; color: #a78bfa;">
                                        <i data-lucide="smartphone" style="width: 15px; height: 15px;"></i> Mobile Background Image
                                    </label>
                                    <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-bottom: 6px;">Recommended vertical ratio (9:16 or 4:5, ~750x1000px)</span>
                                    <input type="file" id="slide_mobile_image" name="mobile_image" class="form-control" accept="image/*">
                                    <div style="margin-top: 8px;">
                                        <label for="slide_mobile_image_url" style="font-size: 0.8rem;">Or Mobile Image URL</label>
                                        <input type="text" id="slide_mobile_image_url" name="mobile_image_url" class="form-control" placeholder="https://pub-...r2.dev/mobile-hero.webp">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group" style="max-width: 200px;">
                            <label for="slide_sort">Sort Order</label>
                            <input type="number" id="slide_sort" name="sort_order" class="form-control" value="0">
                        </div>

                        <div style="display: flex; gap: 10px; align-items: center; margin-top: 1rem;">
                            <button type="submit" class="btn-submit" id="btn-slide-submit" style="width: auto;">Add Slide</button>
                            <button type="button" class="btn-submit" id="btn-slide-cancel" style="display: none; background-color: var(--admin-input-bg); color: var(--admin-text-muted); width: auto;" onclick="resetSlideForm()">Cancel Edit</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Slides List -->
                <div class="admin-card">
                    <h2>Current Active Slides</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Desktop Image</th>
                                    <th>Mobile Image</th>
                                    <th>Title</th>
                                    <th>Subtitle</th>
                                    <th>CTA Button</th>
                                    <th>Sort</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($slides as $slide)
                                    <tr>
                                        <td>
                                            <div class="image-preview" style="width: 70px; height: 42px; border-radius: 6px; overflow: hidden; border: 1px solid var(--admin-border);">
                                                @if($slide->image_path)
                                                    <img src="{{ site_image($slide->image_path) }}" alt="Desktop Slide" style="width: 100%; height: 100%; object-fit: cover;">
                                                @else
                                                    <span style="font-size:0.7rem; color:var(--admin-text-muted); display:flex; align-items:center; justify-content:center; height:100%;">Default</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="image-preview" style="width: 42px; height: 55px; border-radius: 6px; overflow: hidden; border: 1px solid var(--admin-border);">
                                                @if($slide->mobile_image_path)
                                                    <img src="{{ site_image($slide->mobile_image_path) }}" alt="Mobile Slide" style="width: 100%; height: 100%; object-fit: cover;">
                                                @elseif($slide->image_path)
                                                    <img src="{{ site_image($slide->image_path) }}" alt="Desktop Fallback" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.6;" title="Using Desktop fallback">
                                                @else
                                                    <span style="font-size:0.7rem; color:var(--admin-text-muted); display:flex; align-items:center; justify-content:center; height:100%;">Default</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td><strong>{{ $slide->title }}</strong></td>
                                        <td>{{ Str::limit($slide->subtitle, 45) }}</td>
                                        <td>
                                            @if($slide->button_text)
                                                <span class="badge-webp" style="background-color: var(--accent-orange);">{{ $slide->button_text }} ({{ $slide->button_link }})</span>
                                            @else
                                                <span style="color:var(--admin-text-muted);">None</span>
                                            @endif
                                        </td>
                                        <td>{{ $slide->sort_order }}</td>
                                        <td>
                                            <div class="action-btns" style="display: flex; gap: 6px;">
                                                <button type="button" class="btn-action edit" title="Edit Slide" onclick='editSlide(@json($slide))'>
                                                    <i data-lucide="edit-2"></i>
                                                </button>
                                                <form action="{{ route('admin.slides.delete', $slide->id) }}" method="POST" onsubmit="return confirm('Delete this slide?')">
                                                    @csrf
                                                    <button type="submit" class="btn-action delete" title="Delete"><i data-lucide="trash-2"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; color: var(--admin-text-muted);">No custom slides found. Showing default homepage layout as fallback.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. TESTIMONIALS TAB -->
            <div class="tab-panel @if($activeTab === 'testimonials') active @endif" id="tab-testimonials">
                <!-- AI Sync Testimonials Card -->
                <div class="admin-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                        <div>
                            <h2>Import Google My Business Reviews</h2>
                            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-top: -5px; margin-bottom: 0;">
                                Sync reviews automatically based on the Google My Business link configured in General Settings.
                            </p>
                        </div>
                        <button type="button" id="btn-sync-gmb" class="btn-submit" style="background-color: #4285F4; color: white; display: flex; align-items: center; gap: 8px; width: auto; padding: 0.6rem 1.2rem; font-size: 0.85rem;" onclick="syncGmbReviews()">
                            <i data-lucide="refresh-cw" id="icon-sync-gmb" style="width: 16px; height: 16px;"></i> Sync GMB Reviews via AI
                        </button>
                    </div>
                </div>

                <!-- Add / Edit Testimonial Card -->
                <div class="admin-card" id="testimonial-form-card">
                    <h2 id="testimonial-form-title">Add New Customer Testimonial</h2>
                    <form id="testimonial-form" action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="id" id="testimonial_id" value="">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="test_name">Client Name</label>
                                <input type="text" id="test_name" name="name" class="form-control" required placeholder="Vikram Sharma">
                            </div>
                            <div class="form-group">
                                <label for="test_role">Client Subtitle/Role</label>
                                <input type="text" id="test_role" name="role" class="form-control" placeholder="South City II, Gurgaon">
                            </div>
                            <div class="form-group">
                                <label for="test_rating">Rating (1 to 5 Stars)</label>
                                <select id="test_rating" name="rating" class="form-control" required>
                                    <option value="5">5 Stars</option>
                                    <option value="4">4 Stars</option>
                                    <option value="3">3 Stars</option>
                                    <option value="2">2 Stars</option>
                                    <option value="1">1 Star</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="test_avatar">Client Avatar (Upload File)</label>
                                <input type="file" id="test_avatar" name="avatar" class="form-control" accept="image/*">
                                <div style="margin-top: 10px;">
                                    <label for="test_avatar_url">Or Paste Avatar URL</label>
                                    <input type="text" id="test_avatar_url" name="avatar_url" class="form-control" placeholder="https://images.tenderfold.in/avatar.webp">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="test_sort">Sort Order</label>
                                <input type="number" id="test_sort" name="sort_order" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top:1.25rem;">
                            <label for="test_content">Testimonial Review Content</label>
                            <textarea id="test_content" name="content" class="form-control" required placeholder="Write review feedback here..."></textarea>
                        </div>
                        <div style="display: flex; gap: 12px; margin-top: 1.25rem;">
                            <button type="submit" id="btn-testimonial-submit" class="btn-submit">Add Testimonial</button>
                            <button type="button" id="btn-testimonial-cancel" class="btn-secondary" style="display: none; width: auto; padding: 0.75rem 1.5rem;" onclick="resetTestimonialForm()">Cancel Edit</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Testimonials List -->
                <div class="admin-card">
                    <h2>Current Active Testimonials</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Avatar</th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>Content</th>
                                    <th>Rating</th>
                                    <th>Sort</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($testimonials as $test)
                                    <tr>
                                        <td>
                                            <div class="image-preview" style="border-radius: 50%;">
                                                @if($test->avatar_path)
                                                    <img src="{{ site_image($test->avatar_path) }}" alt="Avatar">
                                                @else
                                                    <!-- Initials -->
                                                    <div style="background-color:var(--primary-teal); color:white; width:100%; height:100%; display:flex; align-items:center; justify-content:center; font-weight:bold;">
                                                        {{ substr($test->name, 0, 1) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td><strong>{{ $test->name }}</strong></td>
                                        <td>{{ $test->role }}</td>
                                        <td>{{ Str::limit($test->content, 80) }}</td>
                                        <td style="color:#ffb100;">
                                            @for($i = 0; $i < $test->rating; $i++)★@endfor
                                        </td>
                                        <td>{{ $test->sort_order }}</td>
                                        <td>
                                            <div class="action-btns">
                                                <button type="button" class="btn-action edit" title="Edit Testimonial" onclick='editTestimonial(@json($test))'>
                                                    <i data-lucide="edit"></i>
                                                </button>
                                                <form action="{{ route('admin.testimonials.delete', $test->id) }}" method="POST" onsubmit="return confirm('Delete this testimonial?')">
                                                    @csrf
                                                    <button type="submit" class="btn-action delete" title="Delete"><i data-lucide="trash-2"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" style="text-align: center; color: var(--admin-text-muted);">No testimonials found in database. Showing defaults as fallback.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 5. SECTION STYLING & BACKGROUNDS TAB -->
            <div class="tab-panel @if($activeTab === 'styling') active @endif" id="tab-styling">
                <form action="{{ route('admin.parameters.update') }}" method="POST">
                    @csrf
                    <div class="admin-card">
                        <h2>Section Styling & Backgrounds</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Customize background values for different sections of your homepage. You can use hexadecimal color codes (e.g. <code>#ffffff</code>), CSS gradients (e.g. <code>linear-gradient(135deg, #eef7f7 0%, #ffffff 100%)</code>), or even image URLs (e.g. <code>url('/storage/uploads/filename.webp') center/cover</code>).
                        </p>
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="index_hero_bg">Home Hero Section Background</label>
                                <input type="text" id="index_hero_bg" name="index_hero_bg" class="form-control" value="{{ $site['index_hero_bg'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="index_services_bg">Home Services Section Background</label>
                                <input type="text" id="index_services_bg" name="index_services_bg" class="form-control" value="{{ $site['index_services_bg'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="index_about_bg">Home About Section Background</label>
                                <input type="text" id="index_about_bg" name="index_about_bg" class="form-control" value="{{ $site['index_about_bg'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="index_why_bg">Home Why Choose Us Background</label>
                                <input type="text" id="index_why_bg" name="index_why_bg" class="form-control" value="{{ $site['index_why_bg'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="index_testimonials_bg">Home Testimonials Background</label>
                                <input type="text" id="index_testimonials_bg" name="index_testimonials_bg" class="form-control" value="{{ $site['index_testimonials_bg'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="index_faq_bg">Home FAQ Background</label>
                                <input type="text" id="index_faq_bg" name="index_faq_bg" class="form-control" value="{{ $site['index_faq_bg'] ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label for="index_cta_bg">Home CTA Section Background</label>
                                <input type="text" id="index_cta_bg" name="index_cta_bg" class="form-control" value="{{ $site['index_cta_bg'] ?? '' }}">
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn-submit">Save Background Styles</button>
                </form>
            </div>

            <!-- 6. PAGE EDITOR TAB -->
            <div class="tab-panel @if($activeTab === 'pagecontents') active @endif" id="tab-pagecontents">
                <form action="{{ route('admin.page-contents.update') }}" method="POST">
                    @csrf

                    {{-- ── HOMEPAGE HERO ────────────────────────────────── --}}
                    <div class="admin-card">
                        <h2>🏠 Homepage — Hero Section</h2>
                        <p style="font-size:0.85rem;color:var(--admin-text-muted);margin-bottom:1.5rem;margin-top:-1rem;">Controls the large hero slider area headline, subtitle, eyebrow badge, and trust statistics.</p>
                        <div class="form-group">
                            <label>Eyebrow Badge Text (above H1)</label>
                            <input type="text" name="contents[home][hero][eyebrow]" class="form-control" placeholder="Premium Laundry & Dry Cleaner in Gurgaon" value="{{ $pageContents->where('page','home')->where('section','hero')->where('key','eyebrow')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Hero Main H1 Title</label>
                            <input type="text" name="contents[home][hero][title]" class="form-control" placeholder="Freshness in Every Single Fold." value="{{ $pageContents->where('page','home')->where('section','hero')->where('key','title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Hero Subtitle / Description</label>
                            <textarea name="contents[home][hero][subtitle]" class="form-control" rows="3" placeholder="Gurgaon's trusted premium laundry, dry cleaning & specialized fabric spa. Doorstep pickup across all major sectors.">{{ $pageContents->where('page','home')->where('section','hero')->where('key','subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                        <hr style="border:0;border-top:1px solid var(--admin-border);margin:1.5rem 0;">
                        <p style="font-size:0.85rem;color:var(--admin-text-muted);margin-bottom:1rem;"><strong>Trust Statistics Row</strong> — Key numbers and value badges.</p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Trust Stat 1 — Number</label>
                                <input type="text" name="contents[home][hero][trust_1_num]" class="form-control" placeholder="50,000+" value="{{ $pageContents->where('page','home')->where('section','hero')->where('key','trust_1_num')->first()->value ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label>Trust Stat 1 — Label</label>
                                <input type="text" name="contents[home][hero][trust_1_label]" class="form-control" placeholder="Garments Delivered" value="{{ $pageContents->where('page','home')->where('section','hero')->where('key','trust_1_label')->first()->value ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label>Trust Stat 2 — Number</label>
                                <input type="text" name="contents[home][hero][trust_2_num]" class="form-control" placeholder="100%" value="{{ $pageContents->where('page','home')->where('section','hero')->where('key','trust_2_num')->first()->value ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label>Trust Stat 2 — Label</label>
                                <input type="text" name="contents[home][hero][trust_2_label]" class="form-control" placeholder="Eco-Friendly Solvents" value="{{ $pageContents->where('page','home')->where('section','hero')->where('key','trust_2_label')->first()->value ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label>Trust Stat 3 — Number</label>
                                <input type="text" name="contents[home][hero][trust_3_num]" class="form-control" placeholder="Sector 49 & 83" value="{{ $pageContents->where('page','home')->where('section','hero')->where('key','trust_3_num')->first()->value ?? '' }}">
                            </div>
                            <div class="form-group">
                                <label>Trust Stat 3 — Label</label>
                                <input type="text" name="contents[home][hero][trust_3_label]" class="form-control" placeholder="Gurgaon Hubs" value="{{ $pageContents->where('page','home')->where('section','hero')->where('key','trust_3_label')->first()->value ?? '' }}">
                            </div>
                        </div>
                    </div>

                    {{-- ── ABOUT / FABRIC CARE ROUTINE SECTION ─────────── --}}
                    <div class="admin-card">
                        <h2>ℹ️ Homepage — About & Fabric Care Section</h2>
                        <div class="form-group">
                            <label>Pre-Heading / Category Tag</label>
                            <input type="text" name="contents[home][about][pre_title]" class="form-control" placeholder="Home & Office" value="{{ $pageContents->where('page', 'home')->where('section', 'about')->where('key', 'pre_title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>About Section Heading</label>
                            <input type="text" name="contents[home][about][title]" class="form-control" placeholder="Care for every fabric, from Monday shirts to wedding silks." value="{{ $pageContents->where('page', 'home')->where('section', 'about')->where('key', 'title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>About Paragraph 1</label>
                            <textarea name="contents[home][about][body_p1]" class="form-control" rows="3" placeholder="Tenderfold is built around one habit: the cloth decides the process...">{{ $pageContents->where('page', 'home')->where('section', 'about')->where('key', 'body_p1')->first()->value ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>About Paragraph 2</label>
                            <textarea name="contents[home][about][body_p2]" class="form-control" rows="3">{{ $pageContents->where('page', 'home')->where('section', 'about')->where('key', 'body_p2')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- ── SERVICES SECTION ────────────────────────────── --}}
                    <div class="admin-card">
                        <h2>🧺 Homepage — Services Grid Section</h2>
                        <div class="form-group">
                            <label>Services Heading</label>
                            <input type="text" name="contents[home][services][title]" class="form-control" placeholder="Laundry, pressing and dry cleaning under one pickup." value="{{ $pageContents->where('page', 'home')->where('section', 'services')->where('key', 'title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Services Intro Text</label>
                            <textarea name="contents[home][services][subtitle]" class="form-control" rows="3" placeholder="From everyday wash & fold to bridal dry cleaning, shoe care, drapes, and rugs in Gurgaon.">{{ $pageContents->where('page', 'home')->where('section', 'services')->where('key', 'subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- ── PROCESS / HOW IT WORKS ──────────────────────── --}}
                    <div class="admin-card">
                        <h2>🔄 Homepage — How It Works (4 Steps)</h2>
                        <div class="form-group">
                            <label>Section Heading</label>
                            <input type="text" name="contents[home][process][heading]" class="form-control" placeholder="How It Works" value="{{ $pageContents->where('page','home')->where('section','process')->where('key','heading')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Section Subtitle</label>
                            <textarea name="contents[home][process][subtitle]" class="form-control" rows="2" placeholder="Four simple steps from doorstep pickup to fresh delivery...">{{ $pageContents->where('page','home')->where('section','process')->where('key','subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- ── TESTIMONIALS SECTION ─────────────────────────── --}}
                    <div class="admin-card">
                        <h2>⭐ Homepage — Testimonials Section</h2>
                        <div class="form-group">
                            <label>Section Heading</label>
                            <input type="text" name="contents[home][testimonials][heading]" class="form-control" placeholder="What Gurgaon Says About Tenderfold" value="{{ $pageContents->where('page','home')->where('section','testimonials')->where('key','heading')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Section Subtitle</label>
                            <textarea name="contents[home][testimonials][subtitle]" class="form-control" rows="2" placeholder="Real reviews from households and professionals across Gurgaon...">{{ $pageContents->where('page','home')->where('section','testimonials')->where('key','subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    {{-- ── FAQ SECTION ──────────────────────────────────── --}}
                    <div class="admin-card">
                        <h2>❓ Homepage — FAQ Section</h2>
                        <div class="form-group">
                            <label>FAQ Section Heading</label>
                            <input type="text" name="contents[home][faq][heading]" class="form-control" placeholder="Frequently Asked Questions" value="{{ $pageContents->where('page','home')->where('section','faq')->where('key','heading')->first()->value ?? '' }}">
                        </div>
                    </div>

                    {{-- ── FINAL CTA BANNER ─────────────────────────────── --}}
                    <div class="admin-card">
                        <h2>🚀 Homepage — Final CTA Banner</h2>
                        <div class="form-group">
                            <label>CTA Heading</label>
                            <input type="text" name="contents[home][cta][heading]" class="form-control" placeholder="20% Off Your First Order" value="{{ $pageContents->where('page','home')->where('section','cta')->where('key','heading')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>CTA Subtitle</label>
                            <textarea name="contents[home][cta][subtitle]" class="form-control" rows="2" placeholder="Schedule a free doorstep pickup in Gurgaon today...">{{ $pageContents->where('page','home')->where('section','cta')->where('key','subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Website Header Elements -->
                    <div class="admin-card">
                        <h2>Website Header & Navigation</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Edit navigation menu link labels and header action button text.
                        </p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Home Menu Label</label>
                                <input type="text" name="contents[header][menu][home]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'home')->first()->value ?? 'Home' }}">
                            </div>
                            <div class="form-group">
                                <label>About Us Menu Label</label>
                                <input type="text" name="contents[header][menu][about]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'about')->first()->value ?? 'About' }}">
                            </div>
                            <div class="form-group">
                                <label>Services Menu Label</label>
                                <input type="text" name="contents[header][menu][services]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'services')->first()->value ?? 'Services' }}">
                            </div>
                            <div class="form-group">
                                <label>Pricing Menu Label</label>
                                <input type="text" name="contents[header][menu][pricing]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'pricing')->first()->value ?? 'Pricing' }}">
                            </div>
                            <div class="form-group">
                                <label>Journal / Blog Menu Label</label>
                                <input type="text" name="contents[header][menu][blog]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'blog')->first()->value ?? 'Journal' }}">
                            </div>
                            <div class="form-group">
                                <label>Contact Us Menu Label</label>
                                <input type="text" name="contents[header][menu][contact]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'contact')->first()->value ?? 'Contact' }}">
                            </div>
                            <div class="form-group">
                                <label>Header Call to Action Button</label>
                                <input type="text" name="contents[header][cta][button_text]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'cta')->where('key', 'button_text')->first()->value ?? 'Schedule a Pickup' }}">
                            </div>
                        </div>
                    </div>

                    <!-- Website Footer Settings -->
                    <div class="admin-card">
                        <h2>Website Footer Blocks</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Edit footer titles, disclaimer, copy and about summary text.
                        </p>
                        <div class="form-group">
                            <label>Footer Column 1 - Description Summary</label>
                            <textarea name="contents[footer][about][description]" class="form-control" rows="3">{{ $pageContents->where('page', 'footer')->where('section', 'about')->where('key', 'description')->first()->value ?? '' }}</textarea>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Column 2 Heading (Quick Links)</label>
                                <input type="text" name="contents[footer][headings][quick_links]" class="form-control" value="{{ $pageContents->where('page', 'footer')->where('section', 'headings')->where('key', 'quick_links')->first()->value ?? 'Quick Links' }}">
                            </div>
                            <div class="form-group">
                                <label>Column 3 Heading (Services / Fabric Care)</label>
                                <input type="text" name="contents[footer][headings][services]" class="form-control" value="{{ $pageContents->where('page', 'footer')->where('section', 'headings')->where('key', 'services')->first()->value ?? 'Services' }}">
                            </div>
                            <div class="form-group">
                                <label>Column 4 Heading (Contact Info)</label>
                                <input type="text" name="contents[footer][headings][contact]" class="form-control" value="{{ $pageContents->where('page', 'footer')->where('section', 'headings')->where('key', 'contact')->first()->value ?? 'Contact Info' }}">
                            </div>
                            <div class="form-group">
                                <label>Footer Copyright suffix (e.g. All Rights Reserved)</label>
                                <input type="text" name="contents[footer][bottom][copyright]" class="form-control" value="{{ $pageContents->where('page', 'footer')->where('section', 'bottom')->where('key', 'copyright')->first()->value ?? 'All Rights Reserved.' }}">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 1rem;">
                            <label>Footer Bottom Disclaimer text</label>
                            <textarea name="contents[footer][bottom][disclaimer]" class="form-control" rows="2">{{ $pageContents->where('page', 'footer')->where('section', 'bottom')->where('key', 'disclaimer')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- About Us Page sections -->
                    <div class="admin-card">
                        <h2>About Us Page - Hero & Story</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Hero Section Pre-heading</label>
                                <input type="text" name="contents[about][hero][pre_title]" class="form-control" value="{{ $pageContents->where('page', 'about')->where('section', 'hero')->where('key', 'pre_title')->first()->value ?? 'WHO WE ARE' }}">
                            </div>
                            <div class="form-group">
                                <label>Hero Section Main Title</label>
                                <input type="text" name="contents[about][hero][title]" class="form-control" value="{{ $pageContents->where('page', 'about')->where('section', 'hero')->where('key', 'title')->first()->value ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 1rem;">
                            <label>Hero Subtitle / Description text</label>
                            <textarea name="contents[about][hero][subtitle]" class="form-control" rows="2">{{ $pageContents->where('page', 'about')->where('section', 'hero')->where('key', 'subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                        <hr style="border: 0; border-top: 1px solid var(--admin-border); margin: 1.5rem 0;">
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Story Pre-heading</label>
                                <input type="text" name="contents[about][story][pre_title]" class="form-control" value="{{ $pageContents->where('page', 'about')->where('section', 'story')->where('key', 'pre_title')->first()->value ?? 'Our Story' }}">
                            </div>
                            <div class="form-group">
                                <label>Story Heading / Title</label>
                                <input type="text" name="contents[about][story][title]" class="form-control" value="{{ $pageContents->where('page', 'about')->where('section', 'story')->where('key', 'title')->first()->value ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 1rem;">
                            <label>Story Paragraph 1</label>
                            <textarea name="contents[about][story][p1]" class="form-control" rows="3">{{ $pageContents->where('page', 'about')->where('section', 'story')->where('key', 'p1')->first()->value ?? '' }}</textarea>
                        </div>
                        <div class="form-group" style="margin-top: 1rem;">
                            <label>Story Paragraph 2</label>
                            <textarea name="contents[about][story][p2]" class="form-control" rows="3">{{ $pageContents->where('page', 'about')->where('section', 'story')->where('key', 'p2')->first()->value ?? '' }}</textarea>
                        </div>
                        <div class="form-group" style="margin-top: 1rem;">
                            <label>Story Paragraph 3</label>
                            <textarea name="contents[about][story][p3]" class="form-control" rows="3">{{ $pageContents->where('page', 'about')->where('section', 'story')->where('key', 'p3')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>About Us Page - Vision, Mission & Values</h2>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Vision Block Title</label>
                                <input type="text" name="contents[about][vision][title]" class="form-control" value="{{ $pageContents->where('page', 'about')->where('section', 'vision')->where('key', 'title')->first()->value ?? 'Our Vision' }}">
                            </div>
                            <div class="form-group">
                                <label>Vision Statement text</label>
                                <textarea name="contents[about][vision][text]" class="form-control" rows="3">{{ $pageContents->where('page', 'about')->where('section', 'vision')->where('key', 'text')->first()->value ?? '' }}</textarea>
                            </div>
                            
                            <div class="form-group" style="margin-top: 1rem;">
                                <label>Mission Block Title</label>
                                <input type="text" name="contents[about][mission][title]" class="form-control" value="{{ $pageContents->where('page', 'about')->where('section', 'mission')->where('key', 'title')->first()->value ?? 'Our Mission' }}">
                            </div>
                            <div class="form-group" style="margin-top: 1rem;">
                                <label>Mission Statement text</label>
                                <textarea name="contents[about][mission][text]" class="form-control" rows="3">{{ $pageContents->where('page', 'about')->where('section', 'mission')->where('key', 'text')->first()->value ?? '' }}</textarea>
                            </div>
                            
                            <div class="form-group" style="margin-top: 1rem;">
                                <label>Values Block Title</label>
                                <input type="text" name="contents[about][values][title]" class="form-control" value="{{ $pageContents->where('page', 'about')->where('section', 'values')->where('key', 'title')->first()->value ?? 'Our Core Values' }}">
                            </div>
                            <div class="form-group" style="margin-top: 1rem;">
                                <label>Core Values Statement text</label>
                                <textarea name="contents[about][values][text]" class="form-control" rows="3">{{ $pageContents->where('page', 'about')->where('section', 'values')->where('key', 'text')->first()->value ?? '' }}</textarea>
                            </div>
                        </div>
                    </div>



                    <button type="submit" class="btn-submit">💾 Save All Page Content</button>
                </form>
            </div>

            <!-- 8. SERVICES MANAGER TAB -->
            <div class="tab-panel @if($activeTab === 'services') active @endif" id="tab-services">
                <!-- Add/Edit Service Card -->
                <div class="admin-card" id="service-form-container">
                    <h2 id="service-form-title">
                        <span id="service-form-title-text">Create New Service Page</span>
                        <button type="button" class="btn-ai-sparkle" id="service-ai-btn" onclick="openAiModal('service')">
                            <i data-lucide="sparkles"></i> Generate with AI
                        </button>
                    </h2>
                    <form id="service-form" action="{{ route('admin.services.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="service-form-method" value="POST">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="ser_service_name">Service Name (e.g. Physiotherapy)</label>
                                <input type="text" id="ser_service_name" name="service_name" class="form-control" required placeholder="Physiotherapy">
                            </div>
                            <div class="form-group">
                                <label for="ser_slug">URL Slug (leave empty to auto-generate)</label>
                                <input type="text" id="ser_slug" name="slug" class="form-control" placeholder="physiotherapy">
                            </div>
                            <div class="form-group">
                                <label for="ser_image">Featured Image File</label>
                                <input type="file" id="ser_image" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="ser_image_url">Or Image URL</label>
                                <input type="text" id="ser_image_url" name="image_url" class="form-control" placeholder="uploads/category/image.jpg">
                            </div>
                            <div class="form-group">
                                <label for="ser_icon">Service Icon File</label>
                                <input type="file" id="ser_icon" name="icon" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="ser_icon_url">Or Icon URL</label>
                                <input type="text" id="ser_icon_url" name="icon_url" class="form-control" placeholder="uploads/category/icon.png">
                            </div>
                            <div class="form-group">
                                <label for="ser_badge">Badge / Promo Tag (optional)</label>
                                <input type="text" id="ser_badge" name="badge" class="form-control" placeholder="Best Rates">
                            </div>
                            <div class="form-group">
                                <label for="ser_sort_order">Sort Order</label>
                                <input type="number" id="ser_sort_order" name="sort_order" class="form-control" value="0">
                            </div>
                            <div class="form-group">
                                <label for="ser_hero_category">Hero Category Tag</label>
                                <input type="text" id="ser_hero_category" name="hero_category" class="form-control" placeholder="ORTHOPAEDIC SURGERY">
                            </div>
                            <div class="form-group">
                                <label for="ser_hero_title">Hero Main Title</label>
                                <input type="text" id="ser_hero_title" name="hero_title" class="form-control" required placeholder="Everyday Laundry, Elevated Care in Gurgaon">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_hero_subtitle">Hero Subtitle</label>
                                <textarea id="ser_hero_subtitle" name="hero_subtitle" class="form-control" rows="2" placeholder="Hygienic individual wash batches, skin-safe detergents, and crisp fold for your daily wear."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ser_rate_value">Key Rate Value (e.g. From ₹80 / kg or From ₹130 / pc)</label>
                                <input type="text" id="ser_rate_value" name="rate_value" class="form-control" placeholder="From ₹80 / kg">
                            </div>
                            <div class="form-group">
                                <label for="ser_max_loan">Key Highlight / Pricing Unit</label>
                                <input type="text" id="ser_max_loan" name="max_loan" class="form-control" placeholder="Weight or Garment">
                            </div>
                            <div class="form-group">
                                <label for="ser_tenure">Turnaround Time (e.g. 24 - 48 Hours)</label>
                                <input type="text" id="ser_tenure" name="tenure" class="form-control" placeholder="24 - 48 Hours">
                            </div>
                            <div class="form-group">
                                <label for="ser_intro_title">Intro Title</label>
                                <input type="text" id="ser_intro_title" name="intro_title" class="form-control" placeholder="Hygienic Washing with Fabric-Safe Care">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_intro_content">Intro Content (HTML Editor)</label>
                                <textarea id="ser_intro_content" name="intro_content" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ser_eligibility_criteria">Key Process Steps / Care Highlights (one per line)</label>
                                <textarea id="ser_eligibility_criteria" name="eligibility_criteria" class="form-control" rows="4" placeholder="Individual Batch Washing&#10;Zero Cross-Contamination&#10;Skin-Safe Hypoallergenic Detergents"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ser_documents_required">Inclusions & Packaging Benefits (one per line)</label>
                                <textarea id="ser_documents_required" name="documents_required" class="form-control" rows="4" placeholder="Free Doorstep Pickup across Gurgaon&#10;Custom Starch & Softener options&#10;Neat Barcoded Packaging"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ser_tips_title">Fabric Tips / Care Guidance Title (optional)</label>
                                <input type="text" id="ser_tips_title" name="tips_title" class="form-control" placeholder="Tips for Daily Laundry & Fabric Longevity">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_tips_content">Tips Section Content (HTML Editor)</label>
                                <textarea id="ser_tips_content" name="tips_content" class="form-control"></textarea>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_summary">Short Summary (shown in lists & footer)</label>
                                <textarea id="ser_summary" name="summary" class="form-control" rows="2" placeholder="Wash, gentle dry & crisp fold for your daily wear, bedsheets, towels, and activewear."></textarea>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_faqs">Service Frequently Asked Questions (FAQs)</label>
                                <textarea id="ser_faqs" name="faqs" class="form-control" rows="5" placeholder="Do you wash my clothes with other customers? | Never. Every order is processed in its own dedicated wash drum to maintain absolute hygiene.&#10;What is the standard turnaround time? | Standard wash & fold is delivered within 24 to 48 hours."></textarea>
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted);">Format: Enter one question and answer per line in <code>Question | Answer</code> format.</span>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_schema_markup">Custom Schema Markup (JSON-LD script)</label>
                                <textarea id="ser_schema_markup" name="schema_markup" class="form-control" rows="3" placeholder='<script type="application/ld+json">...</script>'></textarea>
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted);">Paste the complete <code>&lt;script type="application/ld+json"&gt;...&lt;/script&gt;</code> block to override the default service schema.</span>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <hr style="border-color: var(--admin-border); margin: 1rem 0;">
                                <h4 style="margin-top: 0.5rem; margin-bottom: 0.5rem; color: var(--accent-teal);">SEO Metadata (Optional)</h4>
                            </div>
                            <div class="form-group">
                                <label for="ser_metatitle">Meta Title (SEO)</label>
                                <input type="text" id="ser_metatitle" name="metatitle" class="form-control" placeholder="Laundry Services in Gurgaon | Tenderfold">
                            </div>
                            <div class="form-group">
                                <label for="ser_metakeywords">Meta Keywords (Comma separated)</label>
                                <input type="text" id="ser_metakeywords" name="metakeywords" class="form-control" placeholder="laundry gurgaon, wash and fold sector 49, dry cleaning gurgaon">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_metadescription">Meta Description (SEO)</label>
                                <textarea id="ser_metadescription" name="metadescription" class="form-control" rows="2" placeholder="Doorstep wash and fold laundry services in Gurgaon with individual batch hygiene."></textarea>
                            </div>
                            <div class="form-group">

                                <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; margin-top: 1rem;">
                                    <input type="checkbox" id="ser_is_active" name="is_active" value="1" checked style="width: 18px; height: 18px; accent-color: var(--mint-green);">
                                    Active / Visible in navigation
                                </label>
                            </div>
                        </div>

                        <!-- FAQ Repeater Section -->
                        <div style="margin-top: 2rem; border-top: 1px solid var(--admin-border); padding-top: 1.5rem;">
                            <h3>Service FAQs</h3>
                            <div id="faq-repeater-container" style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                                <!-- FAQ items will be added here dynamically -->
                            </div>
                            <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="addFaqInput()">
                                <i data-lucide="plus" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i> Add FAQ Question
                            </button>
                        </div>

                        <div style="margin-top: 2rem; display: flex; gap: 10px;">
                            <button type="submit" class="btn-submit" id="service-submit-btn">Create Service Page</button>
                            <button type="button" class="btn-submit" id="service-cancel-btn" style="background-color: var(--admin-input-bg); color: #fff; display: none;" onclick="cancelServiceEdit()">Cancel Edit</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Services Table -->
                <div class="admin-card">
                    <h2>Active Service Pages</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Order</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Rate</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($services as $s)
                                    <tr>
                                        <td>{{ $s->sort_order }}</td>
                                        <td style="font-weight: 500;">{{ $s->service_name }}</td>
                                        <td style="color: var(--admin-text-muted);">{{ $s->slug }}</td>
                                        <td>{{ $s->rate_value ?? 'N/A' }}</td>
                                        <td>
                                            @if($s->is_active)
                                                <span style="color: var(--accent-teal); font-size: 0.85rem; font-weight: 600;">● Active</span>
                                            @else
                                                <span style="color: var(--admin-text-muted); font-size: 0.85rem;">● Inactive</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="action-btns" style="justify-content: flex-end;">
                                                <button class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: var(--admin-input-bg); color: #fff;" 
                                                    onclick="editService({{ json_encode($s) }})">
                                                    Edit
                                                </button>
                                                <button class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: rgba(66, 133, 244, 0.1); color: #4285f4; border: 1px solid rgba(66, 133, 244, 0.3);" 
                                                    onclick="openGmbShareModal('service', {{ json_encode($s) }})">
                                                    GMB
                                                </button>
                                                <form action="{{ route('admin.services.delete', $s->id) }}" method="POST" onsubmit="return confirm('Delete this service page? This action cannot be undone.')">
                                                    @csrf
                                                    <button type="submit" class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: rgba(239, 68, 68, 0.2); color: #f87171;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: var(--admin-text-muted);">No service pages configured.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 9. TREATMENTS MANAGER TAB -->
            <div class="tab-panel @if($activeTab === 'treatments') active @endif" id="tab-treatments">
                <div class="admin-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h2 id="treatment-form-title-text" style="margin:0;">Add New Treatment Page</h2>
                        <button type="button" class="btn-ai-sparkle" onclick="openAiModal('treatment')"><i data-lucide="sparkles"></i> Generate Treatment with AI</button>
                    </div>
                    <form id="treatment-form" action="{{ route('admin.treatments.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="treatment-form-method" value="POST">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="treat_name">Treatment Name</label>
                                <input type="text" id="treat_name" name="name" class="form-control" required placeholder="Knee Replacement Surgery">
                            </div>
                            <div class="form-group">
                                <label for="treat_slug">Custom Slug (optional)</label>
                                <input type="text" id="treat_slug" name="slug" class="form-control" placeholder="knee-replacement-surgery">
                            </div>
                            <div class="form-group">
                                <label for="treat_image">Featured Image File</label>
                                <input type="file" id="treat_image" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="treat_image_url">Or Image URL</label>
                                <input type="text" id="treat_image_url" name="image_url" class="form-control" placeholder="uploads/category/image.jpg">
                            </div>
                            <div class="form-group">
                                <label for="treat_metatitle">Meta Title (SEO)</label>
                                <input type="text" id="treat_metatitle" name="metatitle" class="form-control" placeholder="Best Knee Replacement Surgery in Pune">
                            </div>
                            <div class="form-group">
                                <label for="treat_tags">Tags / Keywords (comma separated)</label>
                                <input type="text" id="treat_tags" name="tags" class="form-control" placeholder="knee, surgery, replacement">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="treat_metadescription">Meta Description (SEO)</label>
                            <textarea id="treat_metadescription" name="metadescription" class="form-control" rows="2" placeholder="Looking for Knee Replacement Surgery in Baner, Pune? Consult Dr. Sumit at SumitZ Clinic."></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="treat_description">Detailed Treatment Content</label>
                            <textarea id="treat_description" name="description" class="form-control richtext" rows="10" placeholder="Write treatment overview, symptoms, process..."></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="treat_faqs">Treatment Frequently Asked Questions (FAQs)</label>
                            <textarea id="treat_faqs" name="faqs" class="form-control" rows="5" placeholder="What is the expected recovery timeline? | Physical therapy often shows improvements in 2-4 weeks, while surgical procedures involve structured rehabilitation over 6-12 weeks.&#10;Do I need an advance appointment? | Booking an advance appointment is recommended to minimize wait times."></textarea>
                            <span style="font-size: 0.75rem; color: var(--admin-text-muted);">Format: Enter one question and answer per line in <code>Question | Answer</code> format.</span>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="treat_schema_markup">Custom Schema Markup (JSON-LD)</label>
                            <div style="display: flex; gap: 10px; margin-bottom: 0.5rem;">
                                <button type="button" class="btn-submit" style="background-color: var(--accent-blue); padding: 4px 10px; font-size: 0.8rem;" onclick="generateAiSchemaForType('treatment')">
                                    <i data-lucide="sparkles" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i> Generate with AI
                                </button>
                            </div>
                            <textarea id="treat_schema_markup" name="schema_markup" class="form-control" rows="4" placeholder="<script type='application/ld+json'>...</script>"></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="treat_status" name="status" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                            <label for="treat_status" style="margin-bottom: 0; cursor: pointer;">Is Active / Published</label>
                        </div>

                        <div style="margin-top: 1.5rem; display: flex; gap: 10px;">
                            <button type="submit" class="btn-submit">Save Treatment Page</button>
                            <button type="button" class="btn-submit" id="treat-cancel-btn" style="background-color: var(--admin-input-bg); color: #fff; display: none;" onclick="cancelTreatmentEdit()">Cancel Edit</button>
                        </div>
                    </form>
                </div>

                <div class="admin-card">
                    <h2>Active Treatments</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($treatments as $t)
                                    <tr>
                                        <td>
                                            @if($t->image)
                                                <img src="{{ site_image($t->image) }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                <span style="color: var(--admin-text-muted);">No image</span>
                                            @endif
                                        </td>
                                        <td style="font-weight: 500;">{{ $t->name }}</td>
                                        <td style="color: var(--admin-text-muted);">{{ $t->slug }}</td>
                                        <td>
                                            @if($t->status)
                                                <span style="color: var(--accent-teal); font-size: 0.85rem; font-weight: 600;">● Active</span>
                                            @else
                                                <span style="color: var(--admin-text-muted); font-size: 0.85rem;">● Inactive</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="action-btns" style="justify-content: flex-end;">
                                                <button class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: var(--admin-input-bg); color: #fff;" 
                                                    onclick="editTreatment({{ json_encode($t) }})">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.treatments.delete', $t->id) }}" method="POST" onsubmit="return confirm('Delete this treatment page? This action cannot be undone.')">
                                                    @csrf
                                                    <button type="submit" class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: rgba(239, 68, 68, 0.2); color: #f87171;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: var(--admin-text-muted);">No treatments configured.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 10. SPECIALISTS TAB -->
            <div class="tab-panel @if($activeTab === 'specialists') active @endif" id="tab-specialists">
                <div class="admin-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px;">
                        <h2 id="specialist-form-title-text" style="margin: 0;">Add New Clinic Specialist</h2>
                        <button type="button" class="btn-ai-sparkle" id="specialist-ai-btn" onclick="openAiModal('specialist')"><i data-lucide="sparkles"></i> Generate Specialist with AI</button>
                    </div>
                    <form id="specialist-form" action="{{ route('admin.specialists.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="specialist-form-method" value="POST">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="spec_name">Full Name</label>
                                <input type="text" id="spec_name" name="name" class="form-control" required placeholder="Dr. Sumit Diwan">
                            </div>
                            <div class="form-group">
                                <label for="spec_designation">Designation / Degree</label>
                                <input type="text" id="spec_designation" name="designation" class="form-control" required placeholder="Orthopedic Surgeon, MBBS, MS">
                            </div>
                            <div class="form-group">
                                <label for="spec_slug">Custom Slug (optional)</label>
                                <input type="text" id="spec_slug" name="slug" class="form-control" placeholder="dr-sumit-diwan">
                            </div>
                            <div class="form-group">
                                <label for="spec_image">Profile Picture</label>
                                <input type="file" id="spec_image" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="spec_image_url">Or Image URL</label>
                                <input type="text" id="spec_image_url" name="image_url" class="form-control" placeholder="uploads/collection/dr-sumit.jpg">
                            </div>
                            <div class="form-group">
                                <label for="spec_phone">Phone / Contact</label>
                                <input type="text" id="spec_phone" name="phone" class="form-control" placeholder="+91 9545559434">
                            </div>
                            <div class="form-group">
                                <label for="spec_facebook">Facebook Profile URL</label>
                                <input type="text" id="spec_facebook" name="facebook" class="form-control" placeholder="https://facebook.com/...">
                            </div>
                            <div class="form-group">
                                <label for="spec_twitter">Twitter/X Profile URL</label>
                                <input type="text" id="spec_twitter" name="twitter" class="form-control" placeholder="https://twitter.com/...">
                            </div>
                        </div>

                        <div class="form-grid" style="margin-top: 1rem;">
                            <div class="form-group">
                                <label for="spec_metatitle">Meta Title (SEO)</label>
                                <input type="text" id="spec_metatitle" name="metatitle" class="form-control" placeholder="Dr. Sumit Diwan - Best Orthopedic Doctor in Baner, Pune">
                            </div>
                            <div class="form-group">
                                <label for="spec_tags">Tags (SEO)</label>
                                <input type="text" id="spec_tags" name="tags" class="form-control" placeholder="orthopedic doctor, clinic specialist">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="spec_metadescription">Meta Description (SEO)</label>
                            <textarea id="spec_metadescription" name="metadescription" class="form-control" rows="2" placeholder="Meet Dr. Sumit Diwan, orthopedic specialist at SumitZ Clinic, Pune."></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="spec_description">Detailed Bio / Description</label>
                            <textarea id="spec_description" name="description" class="form-control" rows="6" placeholder="Write doctor qualifications, background, timings..."></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="spec_schema_markup">Custom Schema Markup (JSON-LD)</label>
                            <div style="display: flex; gap: 10px; margin-bottom: 0.5rem;">
                                <button type="button" class="btn-submit" style="background-color: var(--accent-blue); padding: 4px 10px; font-size: 0.8rem;" onclick="generateAiSchemaForType('specialist')">
                                    <i data-lucide="sparkles" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i> Generate with AI
                                </button>
                            </div>
                            <textarea id="spec_schema_markup" name="schema_markup" class="form-control" rows="4" placeholder="<script type='application/ld+json'>...</script>"></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="spec_status" name="status" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                            <label for="spec_status" style="margin-bottom: 0; cursor: pointer;">Is Active / Published</label>
                        </div>

                        <div style="margin-top: 1.5rem; display: flex; gap: 10px;">
                            <button type="submit" class="btn-submit">Save Specialist</button>
                            <button type="button" class="btn-submit" id="spec-cancel-btn" style="background-color: var(--admin-input-bg); color: #fff; display: none;" onclick="cancelSpecialistEdit()">Cancel Edit</button>
                        </div>
                    </form>
                </div>

                <div class="admin-card">
                    <h2>Clinic Specialists</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Designation</th>
                                    <th>Phone</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($specialists as $sp)
                                    <tr>
                                        <td>
                                            @if($sp->image)
                                                <img src="{{ site_image($sp->image) }}" style="width: 40px; height: 40px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                <span style="color: var(--admin-text-muted);">No image</span>
                                            @endif
                                        </td>
                                        <td style="font-weight: 500;">{{ $sp->name }}</td>
                                        <td>{{ $sp->designation }}</td>
                                        <td>{{ $sp->phone ?: 'N/A' }}</td>
                                        <td>
                                            @if($sp->status)
                                                <span style="color: var(--accent-teal); font-size: 0.85rem; font-weight: 600;">● Active</span>
                                            @else
                                                <span style="color: var(--admin-text-muted); font-size: 0.85rem;">● Inactive</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="action-btns" style="justify-content: flex-end;">
                                                <button class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: var(--admin-input-bg); color: #fff;" 
                                                    onclick="editSpecialist({{ json_encode($sp) }})">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.specialists.delete', $sp->id) }}" method="POST" onsubmit="return confirm('Delete this team specialist?')">
                                                    @csrf
                                                    <button type="submit" class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: rgba(239, 68, 68, 0.2); color: #f87171;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: var(--admin-text-muted);">No specialists configured.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 11. CMS PAGES MANAGER TAB -->
            <div class="tab-panel @if($activeTab === 'cms_pages') active @endif" id="tab-cms_pages">
                <div class="admin-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                        <h2 id="cms-form-title-text" style="margin:0;">Add New CMS Page</h2>
                        <button type="button" class="btn-ai-sparkle" onclick="openAiModal('cms')"><i data-lucide="sparkles"></i> Generate CMS Page with AI</button>
                    </div>
                    <form id="cms-form" action="{{ route('admin.cms_pages.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="cms-form-method" value="POST">
                        
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="cms_title">Page Title</label>
                                <input type="text" id="cms_title" name="title" class="form-control" required placeholder="Disclaimer">
                            </div>
                            <div class="form-group">
                                <label for="cms_slug">Custom Slug</label>
                                <input type="text" id="cms_slug" name="slug" class="form-control" required placeholder="disclaimer">
                            </div>
                            <div class="form-group">
                                <label for="cms_image">Page Header/Featured Image</label>
                                <input type="file" id="cms_image" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="cms_image_url">Or Image URL</label>
                                <input type="text" id="cms_image_url" name="image_url" class="form-control" placeholder="uploads/page/disclaimer.jpg">
                            </div>
                            <div class="form-group">
                                <label for="cms_metatitle">Meta Title (SEO)</label>
                                <input type="text" id="cms_metatitle" name="metatitle" class="form-control" placeholder="Terms & Conditions | Tenderfold">
                            </div>
                            <div class="form-group">
                                <label for="cms_metakeywords">Meta Keywords (comma separated)</label>
                                <input type="text" id="cms_metakeywords" name="metakeywords" class="form-control" placeholder="laundry terms, dry cleaning policy">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="cms_metadescription">Meta Description (SEO)</label>
                            <textarea id="cms_metadescription" name="metadescription" class="form-control" rows="2" placeholder="Terms and conditions for Tenderfold laundry and dry cleaning services."></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="cms_description">Page HTML Content</label>
                            <textarea id="cms_description" name="description" class="form-control" rows="10" placeholder="Write page content here..."></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="cms_schema_markup">Custom Schema Markup (JSON-LD)</label>
                            <div style="display: flex; gap: 10px; margin-bottom: 0.5rem;">
                                <button type="button" class="btn-submit" style="background-color: var(--accent-blue); padding: 4px 10px; font-size: 0.8rem;" onclick="generateAiSchemaForType('cms_page')">
                                    <i data-lucide="sparkles" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i> Generate with AI
                                </button>
                            </div>
                            <textarea id="cms_schema_markup" name="schema_markup" class="form-control" rows="4" placeholder="<script type='application/ld+json'>...</script>"></textarea>
                        </div>

                        <div class="form-group" style="margin-top: 1rem; display: flex; align-items: center; gap: 8px;">
                            <input type="checkbox" id="cms_status" name="status" value="1" checked style="width: 18px; height: 18px; cursor: pointer;">
                            <label for="cms_status" style="margin-bottom: 0; cursor: pointer;">Is Active / Published</label>
                        </div>

                        <div style="margin-top: 1.5rem; display: flex; gap: 10px;">
                            <button type="submit" class="btn-submit">Save Page</button>
                            <button type="button" class="btn-submit" id="cms-cancel-btn" style="background-color: var(--admin-input-bg); color: #fff; display: none;" onclick="cancelCmsPageEdit()">Cancel Edit</button>
                        </div>
                    </form>
                </div>

                <div class="admin-card">
                    <h2>CMS Pages</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cmsPages as $cp)
                                    <tr>
                                        <td style="font-weight: 500;">{{ $cp->title }}</td>
                                        <td style="color: var(--admin-text-muted);">{{ $cp->slug }}</td>
                                        <td>
                                            @if($cp->status)
                                                <span style="color: var(--accent-teal); font-size: 0.85rem; font-weight: 600;">● Active</span>
                                            @else
                                                <span style="color: var(--admin-text-muted); font-size: 0.85rem;">● Inactive</span>
                                            @endif
                                        </td>
                                        <td style="text-align: right;">
                                            <div class="action-btns" style="justify-content: flex-end;">
                                                <button class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: var(--admin-input-bg); color: #fff;" 
                                                    onclick="editCmsPage({{ json_encode($cp) }})">
                                                    Edit
                                                </button>
                                                <form action="{{ route('admin.cms_pages.delete', $cp->id) }}" method="POST" onsubmit="return confirm('Delete this CMS page?')">
                                                    @csrf
                                                    <button type="submit" class="btn-submit" style="padding: 4px 10px; font-size: 0.8rem; background-color: rgba(239, 68, 68, 0.2); color: #f87171;">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" style="text-align: center; color: var(--admin-text-muted);">No CMS pages configured.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- DUMMY BANKS TAB Panel (hidden to prevent JS errors if any script references switchTab('banks')) -->
            <div class="tab-panel" id="tab-banks" style="display: none;"></div>

            <!-- 7. BLOG MANAGER TAB -->
            <div class="tab-panel @if($activeTab === 'blogs') active @endif" id="tab-blogs">
                <!-- Add Blog Card -->
                <div class="admin-card" id="blog-form-container">
                    <h2 id="blog-form-title">
                        <span id="blog-form-title-text">Create New Blog Article</span>
                        <button type="button" class="btn-ai-sparkle" id="blog-ai-btn" onclick="openAiModal('blog')">
                            <i data-lucide="sparkles"></i> Generate with AI
                        </button>
                    </h2>
                    <form id="blog-form" action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="_method" id="blog-form-method" value="POST">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="blog_title">Article Title</label>
                                <input type="text" id="blog_title" name="title" class="form-control" required placeholder="Preventing Back Pain in Desk Workers">
                            </div>
                            <div class="form-group">
                                <label for="blog_slug">URL Slug (leave empty to auto-generate)</label>
                                <input type="text" id="blog_slug" name="slug" class="form-control" placeholder="preventing-back-pain-desk-workers">
                            </div>
                            <div class="form-group">
                                <label for="blog_category">Category</label>
                                <select id="blog_category" name="category" class="form-control" required>
                                    <option value="Orthopaedics">Orthopaedics</option>
                                    <option value="Physiotherapy">Physiotherapy</option>
                                    <option value="Spine & Disc Care">Spine & Disc Care</option>
                                    <option value="Sports Injuries">Sports Injuries</option>
                                    <option value="Joint Recovery">Joint Recovery</option>
                                    <option value="Healthy Tips">Healthy Tips</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="blog_published_at">Publish Date</label>
                                <input type="datetime-local" id="blog_published_at" name="published_at" class="form-control" value="{{ date('Y-m-d\TH:i') }}">
                            </div>
                            <div class="form-group">
                                <label for="blog_image">Cover Image (Upload File)</label>
                                <input type="file" id="blog_image" name="image" class="form-control" accept="image/*">
                            </div>
                            <div class="form-group">
                                <label for="blog_image_url">Or Paste Cover Image URL</label>
                                <input type="text" id="blog_image_url" name="image_url" class="form-control" placeholder="https://images.tenderfold.in/cover.webp">
                            </div>
                        </div>
                        <h4 style="margin-top: 1.5rem; margin-bottom: 0.5rem; color: var(--accent-teal);">SEO Metadata (Optional)</h4>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="blog_metatitle">Meta Title (SEO)</label>
                                <input type="text" id="blog_metatitle" name="metatitle" class="form-control" placeholder="Article Meta Title">
                            </div>
                            <div class="form-group">
                                <label for="blog_metakeywords">Meta Keywords (Comma separated)</label>
                                <input type="text" id="blog_metakeywords" name="metakeywords" class="form-control" placeholder="laundry, dry cleaning, gurgaon">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="blog_metadescription">Meta Description (SEO)</label>
                                <textarea id="blog_metadescription" name="metadescription" class="form-control" rows="2" placeholder="SEO optimized description for search engines"></textarea>
                            </div>
                        </div>
                        <div class="form-group" style="margin-top: 1.25rem;">

                            <label for="blog_summary">Short Summary (shown in blog listing card)</label>
                            <textarea id="blog_summary" name="summary" class="form-control" required placeholder="A brief 1-2 sentence description of the article..." rows="2"></textarea>
                        </div>
                        <div class="form-group" style="margin-top: 1.25rem;">
                            <label for="blog_schema_markup">Custom Schema Markup (JSON-LD script)</label>
                            <textarea id="blog_schema_markup" name="schema_markup" class="form-control" placeholder='<script type="application/ld+json">...</script>' rows="3"></textarea>
                            <span style="font-size: 0.75rem; color: var(--admin-text-muted);">Paste the complete <code>&lt;script type="application/ld+json"&gt;...&lt;/script&gt;</code> block to override the default blog posting schema.</span>
                        </div>
                        <div class="form-group" style="margin-top: 1.25rem;">
                            <label for="blog_content">Article Content (HTML Editor)</label>
                            <textarea id="blog_content" name="content" class="form-control" placeholder="Write article content here..."></textarea>
                        </div>
                        <div style="margin-top: 1.5rem; display: flex; gap: 10px;">
                            <button type="submit" class="btn-submit" id="blog-submit-btn">Publish Blog Article</button>
                            <button type="button" class="btn-submit" id="blog-cancel-btn" style="background-color: var(--admin-input-bg); color: #fff; display: none;" onclick="cancelBlogEdit()">Cancel Edit</button>
                        </div>
                    </form>
                </div>

                <!-- Existing Blogs List -->
                <div class="admin-card">
                    <h2>Published Blog Articles</h2>
                    <div class="admin-table-wrap" id="blogs-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Cover Image</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Slug</th>
                                    <th>Published Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="blogs-table-body">
                                @forelse($blogs as $b)
                                    <tr id="blog-row-{{ $b->id }}">
                                        <td>
                                            <div class="image-preview">
                                                @if($b->image_path)
                                                    <img src="{{ site_image($b->image_path) }}" alt="Cover">
                                                @else
                                                    <span style="font-size:0.75rem; color:var(--admin-text-muted);">No Image</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td><strong>{{ $b->title }}</strong></td>
                                        <td><span class="badge-webp" style="background-color: var(--primary-teal);">{{ $b->category }}</span></td>
                                        <td><code>{{ $b->slug }}</code></td>
                                        <td>{{ $b->published_at ? $b->published_at->format('M d, Y H:i') : $b->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            <div class="action-btns">
                                                <button type="button" class="btn-action edit" title="Edit Article" onclick="editBlog({{ $b->id }})"><i data-lucide="edit"></i></button>
                                                <button type="button" class="btn-action" style="background-color: rgba(66, 133, 244, 0.1); color: #4285f4; border: 1px solid rgba(66, 133, 244, 0.3);" title="Share to Google Business Profile" 
                                                    onclick="openGmbShareModal('blog', {id: {{ $b->id }}, title: '{{ addslashes($b->title) }}', summary: ''})"><i data-lucide="share-2"></i></button>
                                                <form action="{{ route('admin.blogs.delete', $b->id) }}" method="POST" onsubmit="return confirm('Delete this blog post?')">
                                                    @csrf
                                                    <button type="submit" class="btn-action delete" title="Delete"><i data-lucide="trash-2"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: var(--admin-text-muted);">No blog posts found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="admin-pagination" style="margin-top: 1.5rem; display: flex; justify-content: center; align-items: center; gap: 1rem;">
                        @if ($blogs->onFirstPage())
                            <span class="btn-action" style="opacity: 0.5; cursor: not-allowed; padding: 0.5rem 1rem;"><i data-lucide="chevron-left"></i> Previous</span>
                        @else
                            <a href="{{ $blogs->previousPageUrl() }}#tab-blogs" class="btn-action" style="padding: 0.5rem 1rem; color: #fff; background: var(--admin-input-bg); border-radius: var(--radius-md);"><i data-lucide="chevron-left"></i> Previous</a>
                        @endif

                        <span style="color: var(--admin-text-muted); font-size: 0.9rem;">Page {{ $blogs->currentPage() }} of {{ $blogs->lastPage() }}</span>

                        @if ($blogs->hasMorePages())
                            <a href="{{ $blogs->nextPageUrl() }}#tab-blogs" class="btn-action" style="padding: 0.5rem 1rem; color: #fff; background: var(--admin-input-bg); border-radius: var(--radius-md);">Next <i data-lucide="chevron-right"></i></a>
                        @else
                            <span class="btn-action" style="opacity: 0.5; cursor: not-allowed; padding: 0.5rem 1rem;">Next <i data-lucide="chevron-right"></i></span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 8. MEDIA MANAGER TAB -->
            <div class="tab-panel @if($activeTab === 'media') active @endif" id="tab-media">
                <div class="admin-card">
                    <h2>
                        <span>Upload Media File</span>
                        <button type="button" class="btn-ai-sparkle" onclick="openAiModal('image')">
                            <i data-lucide="sparkles"></i> Generate Image with AI
                        </button>
                    </h2>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                        Upload any JPEG, PNG, GIF, WebP, or SVG image. Standard raster formats are automatically compressed and converted to the highly efficient <strong>WebP format</strong> on the server.
                    </p>
                    <form id="media-upload-form" action="{{ route('admin.media.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div style="border: 2px dashed var(--admin-border); padding: 3rem; text-align: center; border-radius: var(--radius-lg); background: rgba(255,255,255,0.01); cursor: pointer;" onclick="document.getElementById('media-file-input').click()">
                            <i data-lucide="upload-cloud" style="width: 48px; height: 48px; color: var(--mint-green); margin-bottom: 1rem;"></i>
                            <p style="margin: 0; font-weight: 500;">Click to select an image from your computer</p>
                            <p style="margin: 5px 0 0 0; font-size: 0.8rem; color: var(--admin-text-muted);">Maximum size: 10 MB (JPEG, PNG, GIF, WEBP, SVG)</p>
                            <input type="file" id="media-file-input" name="file" style="display: none;" onchange="uploadMediaFile(this)">
                        </div>
                    </form>
                    <div id="media-upload-progress" style="display: none; margin-top: 1rem;">
                        <div style="background-color: var(--admin-border); border-radius: 10px; height: 6px; width: 100%; overflow: hidden;">
                            <div id="media-progress-bar" style="background-color: var(--mint-green); height: 100%; width: 0%; transition: width 0.3s ease;"></div>
                        </div>
                        <p style="font-size: 0.8rem; margin-top: 5px; color: var(--mint-green);">Uploading and optimizing image...</p>
                    </div>
                </div>

                <div class="admin-card">
                    <h2 style="display: flex; justify-content: space-between; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <span>Media Library</span>
                        <button type="button" class="btn-ai-sparkle" style="background: var(--blue-gradient, linear-gradient(135deg, #2563eb 0%, #3b82f6 100%)); border-color: #2563eb;" onclick="syncLocalImagesToR2(this)">
                            <i data-lucide="refresh-cw"></i> Sync Local Images to R2
                        </button>
                    </h2>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                        Click on any image's <strong>Copy URL</strong> button to use its link in slider images, blog covers, page contents, or background section styles.
                    </p>
                    
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1.25rem;">
                        @forelse($mediaFiles as $media)
                            <div style="background: rgba(255,255,255,0.01); border: 1px solid var(--admin-border); border-radius: var(--radius-md); overflow: hidden; display: flex; flex-direction: column;">
                                <div style="height: 120px; background: rgba(0,0,0,0.2); display: flex; align-items: center; justify-content: center; overflow: hidden; position: relative;">
                                    <img src="{{ $media['url'] }}" alt="{{ $media['name'] }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    <span class="badge-webp" style="position: absolute; bottom: 5px; right: 5px; font-size: 0.65rem;">{{ strtoupper(pathinfo($media['name'], PATHINFO_EXTENSION)) }}</span>
                                </div>
                                <div style="padding: 10px; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                                    <div>
                                        <p style="margin: 0; font-size: 0.8rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $media['name'] }}">{{ $media['name'] }}</p>
                                        <p style="margin: 2px 0 0 0; font-size: 0.7rem; color: var(--admin-text-muted);">{{ round($media['size'] / 1024, 1) }} KB</p>
                                    </div>
                                    <button class="btn-submit" style="width: 100%; padding: 5px; font-size: 0.75rem; margin-top: 10px; border-radius: 4px; display: flex; align-items: center; justify-content: center; gap: 4px;" onclick="copyToClipboard('{{ $media['url'] }}', this)">
                                        <i data-lucide="copy" style="width: 12px; height: 12px;"></i> Copy URL
                                    </button>
                                </div>
                            </div>
                        @empty
                            <p style="grid-column: 1 / -1; text-align: center; color: var(--admin-text-muted); padding: 20px 0;">No media files uploaded yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- LANDING PAGES TAB -->
            <div class="tab-panel @if($activeTab === 'landing') active @endif" id="tab-landing">
                <!-- Create New Landing Page Card -->
                <div class="admin-card">
                    <h2>Create New Landing Page</h2>
                    <form action="{{ route('admin.landing.store') }}" method="POST">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="landing_title">Page Title</label>
                                <input type="text" id="landing_title" name="title" class="form-control" required placeholder="e.g. Express Dry Cleaning in Sector 83" oninput="generateLandingSlug(this.value)">
                            </div>
                            <div class="form-group">
                                <label for="landing_slug">URL Slug</label>
                                <input type="text" id="landing_slug" name="slug" class="form-control" required placeholder="e.g. express-dry-cleaning-sector-83">
                            </div>
                            <div class="form-group">
                                <label for="landing_layout">Layout Structure Type</label>
                                <select id="landing_layout" name="layout_type" class="form-control" required>
                                    <option value="home">Homepage Structure (Hero, Features, About, CTA, FAQs)</option>
                                    <option value="about">About Page Structure (Hero, Story, Vision/Mission/Values, Team, CTA)</option>
                                    <option value="service">Service Structure (Hero, Program Details, Why Us, Steps, CTA)</option>
                                    <option value="specialist">Specialist Structure (Hero, Biography, Credentials, Success Stories, CTA)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="landing_meta">Meta Description</label>
                                <input type="text" id="landing_meta" name="meta_description" class="form-control" placeholder="SEO optimized description for search results">
                            </div>
                        </div>
                        <button type="submit" class="btn-submit" style="margin-top: 1rem; width: auto; padding: 0.6rem 1.5rem;">Create Empty Landing Page</button>
                    </form>
                </div>

                <!-- Existing Landing Pages List -->
                <div class="admin-card">
                    <h2>Manage Landing Pages</h2>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                        Edit generated layouts or trigger Google Gemini AI to write page sections based on prompts.
                    </p>
                    
                    @if($landingPages->isEmpty())
                        <p style="text-align: center; color: var(--admin-text-muted); padding: 2rem 0;">No landing pages created yet.</p>
                    @else
                        <div class="admin-table-wrap">
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Page Title</th>
                                        <th>URL Route</th>
                                        <th>Layout Structure</th>
                                        <th>AI Content Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($landingPages as $lp)
                                        <tr>
                                            <td>
                                                <strong>{{ $lp->title }}</strong>
                                                <br>
                                                <small style="color: var(--admin-text-muted);">ID: {{ $lp->id }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('landing.show', $lp->slug) }}" target="_blank" style="color: var(--mint-green); text-decoration: underline;">
                                                    /l/{{ $lp->slug }}
                                                </a>
                                            </td>
                                            <td>
                                                <span class="badge" style="background: rgba(92, 203, 179, 0.1); color: var(--mint-green);">
                                                    {{ ucfirst($lp->layout_type) }} Layout
                                                </span>
                                            </td>
                                            <td>
                                                @if(empty($lp->content))
                                                    <span class="badge" style="background: rgba(232, 92, 36, 0.1); color: #e85c24;">Empty Layout</span>
                                                @else
                                                    <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6;">Generated via AI</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-btns">
                                                    <button type="button" class="btn-action edit" onclick="toggleEditLandingForm('{{ $lp->id }}')" title="Edit Layout Content">
                                                        <i data-lucide="edit-3"></i> Edit
                                                    </button>
                                                    <button type="button" class="btn-action" style="background: rgba(92, 203, 179, 0.1); color: var(--mint-green); display: inline-flex; align-items: center; gap: 4px;" onclick="openLandingAiModal('{{ $lp->id }}', '{{ $lp->title }}')" title="Generate/Rewrite with Gemini AI">
                                                        <i data-lucide="sparkles"></i> Generate AI
                                                    </button>
                                                    <button type="button" class="btn-action" style="background: rgba(66, 133, 244, 0.1); color: #4285f4; display: inline-flex; align-items: center; gap: 4px;" onclick="openGmbShareModal('landing', {{ json_encode($lp) }})" title="Share to Google Business Profile">
                                                        <i data-lucide="share-2"></i> GMB
                                                    </button>
                                                    <form action="{{ route('admin.landing.delete', $lp->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this landing page?');" style="margin: 0;">
                                                        @csrf
                                                        <button type="submit" class="btn-action delete" title="Delete Page">
                                                            <i data-lucide="trash-2"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>

                                        <!-- Expandable Landing Page Content Editor Row -->
                                        <tr id="landing-editor-{{ $lp->id }}" class="lead-detail-row" style="display: none; background-color: var(--admin-dark-sidebar);">
                                            <td colspan="5" style="padding: 1.5rem; border-top: 1px dashed var(--admin-border); border-bottom: 1px dashed var(--admin-border);">
                                                <form action="{{ route('admin.landing.update', $lp->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="layout_type" value="{{ $lp->layout_type }}">
                                                    
                                                    <h3 style="color: var(--mint-green); margin-bottom: 1rem;">Edit Content - {{ $lp->title }}</h3>
                                                    <div class="form-grid" style="margin-bottom: 1rem;">
                                                        <div class="form-group">
                                                            <label>Page Title</label>
                                                            <input type="text" name="title" class="form-control" value="{{ $lp->title }}" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>URL Route Slug</label>
                                                            <input type="text" name="slug" class="form-control" value="{{ $lp->slug }}" required>
                                                        </div>
                                                        <div class="form-group" style="grid-column: span 2;">
                                                            <label>Meta Description</label>
                                                            <input type="text" name="meta_description" class="form-control" value="{{ $lp->meta_description }}">
                                                        </div>
                                                        <div class="form-group" style="grid-column: span 2;">
                                                            <label>Custom Schema Markup (JSON-LD script)</label>
                                                            <textarea name="schema_markup" class="form-control" rows="3" placeholder='<script type="application/ld+json">...</script>'>{{ $lp->schema_markup ?? '' }}</textarea>
                                                            <span style="font-size: 0.75rem; color: var(--admin-text-muted);">Paste the complete <code>&lt;script type="application/ld+json"&gt;...&lt;/script&gt;</code> block.</span>
                                                        </div>
                                                    </div>

                                                    @if($lp->layout_type === 'home')
                                                        <!-- Home Layout Editor Fields -->
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>Hero Title</label>
                                                            <input type="text" name="content[hero_title]" class="form-control" value="{{ $lp->content['hero_title'] ?? '' }}">
                                                        </div>
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>Hero Subtitle</label>
                                                            <textarea name="content[hero_subtitle]" class="form-control" rows="2">{{ $lp->content['hero_subtitle'] ?? '' }}</textarea>
                                                        </div>
                                                        
                                                        <h4 style="color: var(--mint-green); margin: 1.5rem 0 0.5rem 0; font-size: 0.95rem; text-transform: uppercase;">Features Section (Grid of 4)</h4>
                                                        @for($i = 0; $i < 4; $i++)
                                                            <div class="form-grid" style="background: rgba(0,0,0,0.15); padding: 0.75rem; border-radius: 4px; margin-bottom: 0.5rem; border: 1px solid var(--admin-border);">
                                                                <div class="form-group">
                                                                    <label>Feature {{ $i + 1 }} Title</label>
                                                                    <input type="text" name="content[features][{{ $i }}][title]" class="form-control" value="{{ $lp->content['features'][$i]['title'] ?? '' }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Feature {{ $i + 1 }} Icon (Lucide name)</label>
                                                                    <input type="text" name="content[features][{{ $i }}][icon]" class="form-control" value="{{ $lp->content['features'][$i]['icon'] ?? 'shield' }}">
                                                                </div>
                                                                <div class="form-group" style="grid-column: span 2;">
                                                                    <label>Feature {{ $i + 1 }} Description</label>
                                                                    <textarea name="content[features][{{ $i }}][desc]" class="form-control" rows="1">{{ $lp->content['features'][$i]['desc'] ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endfor

                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>About Us Section Title</label>
                                                            <input type="text" name="content[about_title]" class="form-control" value="{{ $lp->content['about_title'] ?? '' }}">
                                                        </div>
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>About Us Section Text</label>
                                                            <textarea name="content[about_text]" class="form-control" rows="4">{{ $lp->content['about_text'] ?? '' }}</textarea>
                                                        </div>
                                                        
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>CTA Section Title</label>
                                                            <input type="text" name="content[cta_title]" class="form-control" value="{{ $lp->content['cta_title'] ?? '' }}">
                                                        </div>
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>CTA Section Text</label>
                                                            <textarea name="content[cta_text]" class="form-control" rows="2">{{ $lp->content['cta_text'] ?? '' }}</textarea>
                                                        </div>

                                                        <h4 style="color: var(--mint-green); margin: 1.5rem 0 0.5rem 0; font-size: 0.95rem; text-transform: uppercase;">FAQ Section (3 items)</h4>
                                                        @for($i = 0; $i < 3; $i++)
                                                            <div style="background: rgba(0,0,0,0.15); padding: 0.75rem; border-radius: 4px; margin-bottom: 0.5rem; border: 1px solid var(--admin-border);">
                                                                <div class="form-group">
                                                                    <label>FAQ {{ $i + 1 }} Question</label>
                                                                    <input type="text" name="content[faqs][{{ $i }}][q]" class="form-control" value="{{ $lp->content['faqs'][$i]['q'] ?? '' }}">
                                                                </div>
                                                                <div class="form-group" style="margin-top: 0.5rem;">
                                                                    <label>FAQ {{ $i + 1 }} Answer</label>
                                                                    <textarea name="content[faqs][{{ $i }}][a]" class="form-control" rows="2">{{ $lp->content['faqs'][$i]['a'] ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    @elseif($lp->layout_type === 'service')
                                                         <!-- Service Layout Editor Fields -->
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Hero Title</label>
                                                             <input type="text" name="content[hero_title]" class="form-control" value="{{ $lp->content['hero_title'] ?? '' }}">
                                                         </div>
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Hero Subtitle</label>
                                                             <textarea name="content[hero_subtitle]" class="form-control" rows="2">{{ $lp->content['hero_subtitle'] ?? '' }}</textarea>
                                                         </div>
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Program Details Section Title</label>
                                                             <input type="text" name="content[service_details_title]" class="form-control" value="{{ $lp->content['service_details_title'] ?? '' }}">
                                                         </div>
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Program Details Section Text</label>
                                                             <textarea name="content[service_details_text]" class="form-control" rows="4">{{ $lp->content['service_details_text'] ?? '' }}</textarea>
                                                         </div>

                                                         <h4 style="color: var(--mint-green); margin: 1.5rem 0 0.5rem 0; font-size: 0.95rem; text-transform: uppercase;">Why Choose Us / Bullets (Grid of 3)</h4>
                                                         @for($i = 0; $i < 3; $i++)
                                                             <div class="form-grid" style="background: rgba(0,0,0,0.15); padding: 0.75rem; border-radius: 4px; margin-bottom: 0.5rem; border: 1px solid var(--admin-border);">
                                                                 <div class="form-group">
                                                                     <label>Bullet {{ $i + 1 }} Title</label>
                                                                     <input type="text" name="content[service_bullets][{{ $i }}][title]" class="form-control" value="{{ $lp->content['service_bullets'][$i]['title'] ?? '' }}">
                                                                 </div>
                                                                 <div class="form-group">
                                                                     <label>Bullet {{ $i + 1 }} Icon (Lucide name)</label>
                                                                     <input type="text" name="content[service_bullets][{{ $i }}][icon]" class="form-control" value="{{ $lp->content['service_bullets'][$i]['icon'] ?? 'check' }}">
                                                                 </div>
                                                                 <div class="form-group" style="grid-column: span 2;">
                                                                     <label>Bullet {{ $i + 1 }} Description</label>
                                                                     <textarea name="content[service_bullets][{{ $i }}][desc]" class="form-control" rows="1">{{ $lp->content['service_bullets'][$i]['desc'] ?? '' }}</textarea>
                                                                 </div>
                                                             </div>
                                                         @endfor

                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Process Steps Section Title</label>
                                                             <input type="text" name="content[process_title]" class="form-control" value="{{ $lp->content['process_title'] ?? '' }}">
                                                         </div>
                                                         <h4 style="color: var(--mint-green); margin: 1.5rem 0 0.5rem 0; font-size: 0.95rem; text-transform: uppercase;">Recovery Steps (3 steps)</h4>
                                                         @for($i = 0; $i < 3; $i++)
                                                             <div class="form-grid" style="background: rgba(0,0,0,0.15); padding: 0.75rem; border-radius: 4px; margin-bottom: 0.5rem; border: 1px solid var(--admin-border);">
                                                                 <div class="form-group">
                                                                     <label>Step {{ $i + 1 }} Title</label>
                                                                     <input type="text" name="content[process_steps][{{ $i }}][title]" class="form-control" value="{{ $lp->content['process_steps'][$i]['title'] ?? '' }}">
                                                                 </div>
                                                                 <div class="form-group" style="grid-column: span 2;">
                                                                     <label>Step {{ $i + 1 }} Description</label>
                                                                     <textarea name="content[process_steps][{{ $i }}][desc]" class="form-control" rows="1">{{ $lp->content['process_steps'][$i]['desc'] ?? '' }}</textarea>
                                                                 </div>
                                                             </div>
                                                         @endfor

                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>CTA Section Title</label>
                                                             <input type="text" name="content[cta_title]" class="form-control" value="{{ $lp->content['cta_title'] ?? '' }}">
                                                         </div>
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>CTA Section Text</label>
                                                             <textarea name="content[cta_text]" class="form-control" rows="2">{{ $lp->content['cta_text'] ?? '' }}</textarea>
                                                         </div>

                                                    @elseif($lp->layout_type === 'specialist')
                                                         <!-- Specialist Layout Editor Fields -->
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Hero Title</label>
                                                             <input type="text" name="content[hero_title]" class="form-control" value="{{ $lp->content['hero_title'] ?? '' }}">
                                                         </div>
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Hero Subtitle</label>
                                                             <textarea name="content[hero_subtitle]" class="form-control" rows="2">{{ $lp->content['hero_subtitle'] ?? '' }}</textarea>
                                                         </div>
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Specialist Bio Title</label>
                                                             <input type="text" name="content[specialist_bio_title]" class="form-control" value="{{ $lp->content['specialist_bio_title'] ?? '' }}">
                                                         </div>
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>Specialist Bio Text</label>
                                                             <textarea name="content[specialist_bio_text]" class="form-control" rows="4">{{ $lp->content['specialist_bio_text'] ?? '' }}</textarea>
                                                         </div>

                                                         <h4 style="color: var(--mint-green); margin: 1.5rem 0 0.5rem 0; font-size: 0.95rem; text-transform: uppercase;">Credentials & Qualifications (3 items)</h4>
                                                         @for($i = 0; $i < 3; $i++)
                                                             <div class="form-grid" style="background: rgba(0,0,0,0.15); padding: 0.75rem; border-radius: 4px; margin-bottom: 0.5rem; border: 1px solid var(--admin-border);">
                                                                 <div class="form-group">
                                                                     <label>Credential {{ $i + 1 }} Title</label>
                                                                     <input type="text" name="content[specialist_qualifications][{{ $i }}][title]" class="form-control" value="{{ $lp->content['specialist_qualifications'][$i]['title'] ?? '' }}">
                                                                 </div>
                                                                 <div class="form-group" style="grid-column: span 2;">
                                                                     <label>Credential {{ $i + 1 }} Detail</label>
                                                                     <textarea name="content[specialist_qualifications][{{ $i }}][desc]" class="form-control" rows="1">{{ $lp->content['specialist_qualifications'][$i]['desc'] ?? '' }}</textarea>
                                                                 </div>
                                                             </div>
                                                         @endfor

                                                         <h4 style="color: var(--mint-green); margin: 1.5rem 0 0.5rem 0; font-size: 0.95rem; text-transform: uppercase;">Case Studies & Success Stories (3 items)</h4>
                                                         @for($i = 0; $i < 3; $i++)
                                                             <div class="form-grid" style="background: rgba(0,0,0,0.15); padding: 0.75rem; border-radius: 4px; margin-bottom: 0.5rem; border: 1px solid var(--admin-border);">
                                                                 <div class="form-group">
                                                                     <label>Patient {{ $i + 1 }} Name/Initials</label>
                                                                     <input type="text" name="content[case_studies][{{ $i }}][patient_name]" class="form-control" value="{{ $lp->content['case_studies'][$i]['patient_name'] ?? '' }}">
                                                                 </div>
                                                                 <div class="form-group">
                                                                     <label>Diagnosis/Case</label>
                                                                     <input type="text" name="content[case_studies][{{ $i }}][case]" class="form-control" value="{{ $lp->content['case_studies'][$i]['case'] ?? '' }}">
                                                                 </div>
                                                                 <div class="form-group" style="grid-column: span 2;">
                                                                     <label>Recovery Outcome</label>
                                                                     <textarea name="content[case_studies][{{ $i }}][result]" class="form-control" rows="1">{{ $lp->content['case_studies'][$i]['result'] ?? '' }}</textarea>
                                                                 </div>
                                                             </div>
                                                         @endfor

                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>CTA Section Title</label>
                                                             <input type="text" name="content[cta_title]" class="form-control" value="{{ $lp->content['cta_title'] ?? '' }}">
                                                         </div>
                                                         <div class="form-group" style="margin-top: 1rem;">
                                                             <label>CTA Section Text</label>
                                                             <textarea name="content[cta_text]" class="form-control" rows="2">{{ $lp->content['cta_text'] ?? '' }}</textarea>
                                                         </div>
                                                    @else
                                                        <!-- About Layout Editor Fields -->
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>Hero Title</label>
                                                            <input type="text" name="content[hero_title]" class="form-control" value="{{ $lp->content['hero_title'] ?? '' }}">
                                                        </div>
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>Hero Subtitle</label>
                                                            <textarea name="content[hero_subtitle]" class="form-control" rows="2">{{ $lp->content['hero_subtitle'] ?? '' }}</textarea>
                                                        </div>

                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>Story Section Title</label>
                                                            <input type="text" name="content[story_title]" class="form-control" value="{{ $lp->content['story_title'] ?? '' }}">
                                                        </div>
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>Story Section Text</label>
                                                            <textarea name="content[story_text]" class="form-control" rows="5">{{ $lp->content['story_text'] ?? '' }}</textarea>
                                                        </div>

                                                        <div class="form-grid" style="margin-top: 1.5rem;">
                                                            <div class="form-group">
                                                                <label>Our Vision Statement</label>
                                                                <textarea name="content[vision]" class="form-control" rows="3">{{ $lp->content['vision'] ?? '' }}</textarea>
                                                            </div>
                                                            <div class="form-group">
                                                                <label>Our Mission Statement</label>
                                                                <textarea name="content[mission]" class="form-control" rows="3">{{ $lp->content['mission'] ?? '' }}</textarea>
                                                            </div>
                                                            <div class="form-group" style="grid-column: span 2; margin-top: 0.5rem;">
                                                                <label>Our Core Values Statement</label>
                                                                <textarea name="content[values]" class="form-control" rows="2">{{ $lp->content['values'] ?? '' }}</textarea>
                                                            </div>
                                                        </div>

                                                        <h4 style="color: var(--mint-green); margin: 1.5rem 0 0.5rem 0; font-size: 0.95rem; text-transform: uppercase;">Team Section (3 members)</h4>
                                                        @for($i = 0; $i < 3; $i++)
                                                            <div class="form-grid" style="background: rgba(0,0,0,0.15); padding: 0.75rem; border-radius: 4px; margin-bottom: 0.5rem; border: 1px solid var(--admin-border);">
                                                                <div class="form-group">
                                                                    <label>Member {{ $i + 1 }} Name</label>
                                                                    <input type="text" name="content[team][{{ $i }}][name]" class="form-control" value="{{ $lp->content['team'][$i]['name'] ?? '' }}">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Member {{ $i + 1 }} Role</label>
                                                                    <input type="text" name="content[team][{{ $i }}][role]" class="form-control" value="{{ $lp->content['team'][$i]['role'] ?? '' }}">
                                                                </div>
                                                                <div class="form-group" style="grid-column: span 2; margin-top: 0.5rem;">
                                                                    <label>Member {{ $i + 1 }} Bio</label>
                                                                    <textarea name="content[team][{{ $i }}][bio]" class="form-control" rows="2">{{ $lp->content['team'][$i]['bio'] ?? '' }}</textarea>
                                                                </div>
                                                            </div>
                                                        @endfor

                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>CTA Section Title</label>
                                                            <input type="text" name="content[cta_title]" class="form-control" value="{{ $lp->content['cta_title'] ?? '' }}">
                                                        </div>
                                                        <div class="form-group" style="margin-top: 1rem;">
                                                            <label>CTA Section Text</label>
                                                            <textarea name="content[cta_text]" class="form-control" rows="2">{{ $lp->content['cta_text'] ?? '' }}</textarea>
                                                        </div>
                                                    @endif

                                                    <button type="submit" class="btn-submit" style="margin-top: 1rem; width: auto; padding: 0.6rem 1.5rem;">Save Landing Page Content</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- SEO OPTIMIZER TAB -->
            <div class="tab-panel @if($activeTab === 'seo') active @endif" id="tab-seo">
                <div class="admin-card">
                    <h2><i data-lucide="trending-up" style="display: inline-block; vertical-align: middle; margin-right: 5px;"></i> AI SEO Recommendations & Link-Building Blueprint</h2>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                        Enter the target keywords or topics you want to rank for. Our AI will analyze search intent and draft a comprehensive blueprint of content ideas, guest articles, Quora answers, social copy, bookmarking platforms, and backlink opportunities.
                    </p>
                    <div class="form-group">
                        <label for="seo_keywords">Target Keywords (comma-separated or one per line)</label>
                        <textarea id="seo_keywords" class="form-control" rows="3" placeholder="e.g. laundry gurgaon, dry cleaning sector 49, bridal lehenga dry clean, vacuum steam pressing"></textarea>
                    </div>
                    <button type="button" class="btn-ai-sparkle" onclick="generateSeoRecommendations()" style="margin-top: 1rem; padding: 0.75rem 1.5rem; font-size: 0.95rem;">
                        <i data-lucide="sparkles"></i> Generate SEO Blueprint
                    </button>
                </div>

                <div class="admin-card" id="seo-loader" style="display: none; padding: 4rem 2rem; text-align: center;">
                    <div class="ai-sparkle-loader" style="margin: 0 auto 1.5rem auto;"></div>
                    <h3 style="margin: 0; font-size: 1.25rem;">Analyzing Keywords & Formulating SEO Blueprint...</h3>
                    <p style="color: var(--admin-text-muted); margin-top: 5px; font-size: 0.9rem;">Gemini is researching backlink strategies and content maps in the background.</p>
                </div>

                <div id="seo-results" style="display: none;">
                    <!-- Quick tabs inside results to switch categories -->
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 15px; margin-bottom: 1.5rem; border-bottom: 1px solid var(--admin-border); padding-bottom: 10px; flex-wrap: wrap;">
                        <div style="display: flex; gap: 8px; overflow-x: auto; flex-grow: 1; padding-bottom: 5px;">
                            <button type="button" class="btn-submit active" id="seo-tab-btn-content" onclick="switchSeoResultTab('content')" style="width: auto; padding: 0.5rem 1rem; font-size: 0.85rem;">Pages & Blogs</button>
                            <button type="button" class="btn-submit" id="seo-tab-btn-backlinks" onclick="switchSeoResultTab('backlinks')" style="width: auto; padding: 0.5rem 1rem; font-size: 0.85rem; background-color: var(--admin-input-bg); color: #fff;">Backlink Targets</button>
                            <button type="button" class="btn-submit" id="seo-tab-btn-quora" onclick="switchSeoResultTab('quora')" style="width: auto; padding: 0.5rem 1rem; font-size: 0.85rem; background-color: var(--admin-input-bg); color: #fff;">Quora & Q&A</button>
                            <button type="button" class="btn-submit" id="seo-tab-btn-social" onclick="switchSeoResultTab('social')" style="width: auto; padding: 0.5rem 1rem; font-size: 0.85rem; background-color: var(--admin-input-bg); color: #fff;">Social & Pinterest</button>
                            <button type="button" class="btn-submit" id="seo-tab-btn-bookmarking" onclick="switchSeoResultTab('bookmarking')" style="width: auto; padding: 0.5rem 1rem; font-size: 0.85rem; background-color: var(--admin-input-bg); color: #fff;">Bookmarking & Syndication</button>
                            <button type="button" class="btn-submit" id="seo-tab-btn-other" onclick="switchSeoResultTab('other')" style="width: auto; padding: 0.5rem 1rem; font-size: 0.85rem; background-color: var(--admin-input-bg); color: #fff;">Growth Tactics</button>
                        </div>
                        <div>
                            <button type="button" class="btn-submit" onclick="downloadSeoPdf()" style="width: auto; padding: 0.5rem 1.25rem; font-size: 0.85rem; background-color: transparent; border: 1px solid #ffffff; color: #ffffff; font-weight: 600; display: inline-flex; align-items: center; gap: 6px; border-radius: var(--radius-md);">
                                <i data-lucide="download" style="width: 16px; height: 16px;"></i> Download PDF Report
                            </button>
                        </div>
                    </div>

                    <!-- Result Panels -->
                    <!-- 1. Content Panel -->
                    <div class="seo-result-panel active" id="seo-panel-content">
                        <div class="admin-card">
                            <h2>On-Site Content Structure Recommendations</h2>
                            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">Create new pages or write detailed blog posts for these target keywords to establish topical authority.</p>
                            <div class="admin-table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 15%;">Type</th>
                                            <th style="width: 25%;">Recommended Title</th>
                                            <th style="width: 20%;">Target Keywords</th>
                                            <th style="width: 40%;">Content Outline & Search Intent</th>
                                        </tr>
                                    </thead>
                                    <tbody id="seo-table-content"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Backlinks Panel -->
                    <div class="seo-result-panel" id="seo-panel-backlinks" style="display: none;">
                        <div class="admin-card">
                            <h2>Third-Party Off-Site Content & Backlinks</h2>
                            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">Draft guest posts or articles to pitch to financial portals and external platforms to build domain authority.</p>
                            <div class="admin-table-wrap">
                                <table class="admin-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 20%;">Platform Type</th>
                                            <th style="width: 30%;">Article Title Idea</th>
                                            <th style="width: 20%;">Target Anchor Keywords</th>
                                            <th style="width: 30%;">Backlink Integration Context</th>
                                        </tr>
                                    </thead>
                                    <tbody id="seo-table-backlinks"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Quora Panel -->
                    <div class="seo-result-panel" id="seo-panel-quora" style="display: none;">
                        <div class="admin-card">
                            <h2>Quora Authority Building & Referral Traffic</h2>
                            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">Answer these questions on Quora to capture high-intent referral traffic and establish financial niche expertise.</p>
                            <div id="seo-cards-quora" style="display: flex; flex-direction: column; gap: 1rem;"></div>
                        </div>
                    </div>

                    <!-- 4. Social & Pinterest Panel -->
                    <div class="seo-result-panel" id="seo-panel-social" style="display: none;">
                        <div class="form-grid">
                            <div class="admin-card">
                                <h2>Pinterest Graphic & Post Layouts</h2>
                                <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">Create high-engagement pins targeting financial boards.</p>
                                <div class="admin-table-wrap">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th>Overlay Bold Text</th>
                                                <th>Visual Graphic Concept</th>
                                                <th>Category</th>
                                            </tr>
                                        </thead>
                                        <tbody id="seo-table-pinterest"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="admin-card">
                                <h2>Written Social Media Posts</h2>
                                <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">Ready-to-use captions and concepts for LinkedIn, Facebook, and Instagram.</p>
                                <div id="seo-cards-social" style="display: flex; flex-direction: column; gap: 1rem;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- 5. Bookmarking & Syndication Panel -->
                    <div class="seo-result-panel" id="seo-panel-bookmarking" style="display: none;">
                        <div class="form-grid">
                            <div class="admin-card">
                                <h2>Bookmarking Websites & Submission Strategy</h2>
                                <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">Share links to your newly written pages on these social bookmarking platforms.</p>
                                <div class="admin-table-wrap">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Platform</th>
                                                <th style="width: 75%;">Tactical Submission Strategy</th>
                                            </tr>
                                        </thead>
                                        <tbody id="seo-table-bookmarking"></tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="admin-card">
                                <h2>Blog Promotion & Syndication Websites</h2>
                                <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">Republish or syndicate your blog posts to reach wider audiences.</p>
                                <div class="admin-table-wrap">
                                    <table class="admin-table">
                                        <thead>
                                            <tr>
                                                <th style="width: 25%;">Platform</th>
                                                <th style="width: 75%;">Tactical Syndication Strategy</th>
                                            </tr>
                                        </thead>
                                        <tbody id="seo-table-syndication"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 6. Other growth tactics -->
                    <div class="seo-result-panel" id="seo-panel-other" style="display: none;">
                        <div class="admin-card">
                            <h2>Growth Tactics & Backlink Submission Blueprints</h2>
                            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">Implement these white-hat link acquisition strategies to grow domain authority.</p>
                            <div id="seo-cards-other" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;"></div>
                        </div>
                    </div>
                </div>

                <!-- XML Sitemap & Robots.txt Manager Card -->
                <div class="admin-card" style="margin-top: 2rem;">
                    <h2><i data-lucide="map" style="display: inline-block; vertical-align: middle; margin-right: 5px;"></i> Live XML Sitemap & Domain Config</h2>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                        Set your target production domain name and regenerate the live <code>sitemap.xml</code> and <code>robots.txt</code> files for search engines like Google & Bing.
                    </p>
                    <form action="{{ route('admin.sitemap.generate') }}" method="POST">
                        @csrf
                        <div class="form-grid" style="grid-template-columns: 2fr 1fr; align-items: end; gap: 1rem; margin-bottom: 1.25rem;">
                            <div class="form-group" style="margin-bottom: 0;">
                                <label for="sitemap_domain">Production Domain Name (with protocol, no trailing slash)</label>
                                <input type="url" id="sitemap_domain" name="sitemap_domain" class="form-control" value="{{ $site['sitemap_domain'] ?? 'https://tenderfold.in' }}" placeholder="https://tenderfold.in" required>
                            </div>
                            <div>
                                <button type="submit" class="btn-submit" style="width: 100%; padding: 0.75rem 1.25rem; font-weight: 600;">
                                    <i data-lucide="refresh-cw" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 4px;"></i> Update & Regenerate Sitemap
                                </button>
                            </div>
                        </div>
                    </form>
                    <div style="display: flex; gap: 1.5rem; margin-top: 15px; font-size: 0.85rem; flex-wrap: wrap;">
                        <span>📄 Sitemap File: <a href="/sitemap.xml" target="_blank" style="color: var(--mint-green); text-decoration: underline; font-weight: 600;">/sitemap.xml</a></span>
                        <span>🤖 Robots File: <a href="/robots.txt" target="_blank" style="color: var(--mint-green); text-decoration: underline; font-weight: 600;">/robots.txt</a></span>
                        <span>🤖 LLM Directory: <a href="/llms.txt" target="_blank" style="color: var(--mint-green); text-decoration: underline; font-weight: 600;">/llms.txt</a></span>
                    </div>
                </div>
            </div>

            @if($hasAccess('analytics'))
            <!-- Google Analytics & GBP Dashboard Tab -->
            <div class="tab-panel @if($activeTab === 'analytics') active @endif" id="tab-analytics">
                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 10px; border-bottom: 1px solid var(--admin-border); padding-bottom: 1rem;">
                    <h2 style="margin: 0; font-size: 1.5rem; display: flex; align-items: center; gap: 8px;"><i data-lucide="bar-chart-3" style="color: var(--mint-green);"></i> Analytics Dashboard</h2>
                    <div style="display: flex; align-items: center; gap: 8px;">
                        <label for="analytics_date_range" style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 0;">Date Range:</label>
                        <select id="analytics_date_range" class="form-control" style="width: auto; padding: 0.4rem 1.5rem; font-size: 0.85rem; background: rgba(255,255,255,0.05); border-color: var(--admin-border); color: #fff; cursor: pointer;" onchange="fetchGoogleAnalytics(this.value)">
                            <option value="last_7_days" selected>Last 7 Days</option>
                            <option value="last_30_days">Last 30 Days</option>
                            <option value="last_90_days">Last 90 Days</option>
                        </select>
                    </div>
                </div>

                <div id="google-analytics-demo-banner" class="alert-success" style="display: none; background-color: rgba(59, 130, 246, 0.1); border-color: rgba(59, 130, 246, 0.3); color: #60a5fa; margin-bottom: 1.5rem;">
                    <i data-lucide="info"></i>
                    <span><strong>Preview Mode:</strong> Showing simulated metrics. Connect your Google Analytics, Search Console, and My Business API credentials below to view live data.</span>
                </div>

                <div id="google-analytics-loader" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 4rem 2rem; background: var(--admin-dark-sidebar); border-radius: 12px; border: 1px solid var(--admin-border); margin-bottom: 2rem;">
                    <div style="width: 40px; height: 40px; border: 3px solid rgba(92, 203, 179, 0.2); border-top-color: var(--mint-green); border-radius: 50%; animation: spin 0.8s linear infinite; margin-bottom: 1rem;"></div>
                    <p style="color: var(--admin-text-muted); font-size: 0.95rem; margin: 0;">Fetching analytics metrics...</p>
                </div>

                <div id="google-analytics-dashboard" style="display: none;">
                    <!-- 1. GA4 Overview Cards -->
                    <div style="margin-bottom: 2rem;">
                        <h3 style="font-size: 1.1rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="globe" style="width: 18px; height: 18px; color: var(--mint-green);"></i> Google Analytics 4 (GA4) Overview
                        </h3>
                        <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem;">
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid var(--mint-green);">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Total Sessions</h4>
                                <p id="ga-sessions" style="font-size: 1.75rem; font-weight: 800; color: #fff; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #3b82f6;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Active Users</h4>
                                <p id="ga-users" style="font-size: 1.75rem; font-weight: 800; color: #3b82f6; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #8b5cf6;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Returning Users</h4>
                                <p id="ga-returning" style="font-size: 1.75rem; font-weight: 800; color: #8b5cf6; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #f59e0b;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Bounce Rate</h4>
                                <p id="ga-bounce" style="font-size: 1.75rem; font-weight: 800; color: #f59e0b; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #10b981;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Avg Time on Site</h4>
                                <p id="ga-time" style="font-size: 1.75rem; font-weight: 800; color: #10b981; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #ec4899;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Pages / Session</h4>
                                <p id="ga-pages-per-session" style="font-size: 1.75rem; font-weight: 800; color: #ec4899; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                        </div>

                        <!-- GA4 Timeline Chart -->
                        <div class="admin-card" style="margin-top: 1rem; padding: 1.25rem;">
                            <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: #fff;">Sessions & Users Timeline</h4>
                            <div style="height: 250px; position: relative;">
                                <canvas id="ga-chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- 2. Google Search Console Section -->
                    <div style="margin-bottom: 2rem;">
                        <h3 style="font-size: 1.1rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="search" style="width: 18px; height: 18px; color: #3b82f6;"></i> Google Search Console (Search Performance)
                        </h3>
                        <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem;">
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-left: 4px solid #3b82f6;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Total Organic Clicks</h4>
                                <p id="gsc-clicks" style="font-size: 1.85rem; font-weight: 800; color: #3b82f6; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-left: 4px solid #8b5cf6;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Total Impressions</h4>
                                <p id="gsc-impressions" style="font-size: 1.85rem; font-weight: 800; color: #8b5cf6; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-left: 4px solid var(--mint-green);">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Avg CTR</h4>
                                <p id="gsc-ctr" style="font-size: 1.85rem; font-weight: 800; color: var(--mint-green); margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-left: 4px solid #f59e0b;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Avg Position</h4>
                                <p id="gsc-position" style="font-size: 1.85rem; font-weight: 800; color: #f59e0b; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                        </div>

                        <!-- GSC Timeline Chart -->
                        <div class="admin-card" style="margin-top: 1rem; padding: 1.25rem;">
                            <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: #fff;">Search Clicks & Impressions Timeline</h4>
                            <div style="height: 250px; position: relative;">
                                <canvas id="gsc-chart"></canvas>
                            </div>
                        </div>

                        <!-- Top Keywords & Top Pages Grid -->
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-top: 1rem;">
                            <!-- Top Keywords -->
                            <div class="admin-card" style="margin-bottom: 0;">
                                <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: #fff;">🔑 Top Organic Search Queries</h4>
                                <div class="admin-table-wrap" style="max-height: 320px; overflow-y: auto;">
                                    <table class="admin-table" style="font-size: 0.82rem;">
                                        <thead>
                                            <tr>
                                                <th>Query</th>
                                                <th style="text-align: right;">Clicks</th>
                                                <th style="text-align: right;">Imps</th>
                                                <th style="text-align: right;">CTR</th>
                                                <th style="text-align: right;">Pos</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gsc-keywords-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- Top Pages -->
                            <div class="admin-card" style="margin-bottom: 0;">
                                <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: #fff;">📄 Top Search Landing Pages</h4>
                                <div class="admin-table-wrap" style="max-height: 320px; overflow-y: auto;">
                                    <table class="admin-table" style="font-size: 0.82rem;">
                                        <thead>
                                            <tr>
                                                <th>Page URL</th>
                                                <th style="text-align: right;">Clicks</th>
                                                <th style="text-align: right;">Imps</th>
                                                <th style="text-align: right;">CTR</th>
                                            </tr>
                                        </thead>
                                        <tbody id="gsc-pages-tbody"></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 3. Google My Business (GMB) / Local Business Insights -->
                    <div style="margin-bottom: 2rem;">
                        <h3 style="font-size: 1.1rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="map-pin" style="width: 18px; height: 18px; color: #ef4444;"></i> Google My Business (GMB) Local Insights
                        </h3>
                        <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem;">
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #ef4444;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Profile Views</h4>
                                <p id="gmb-visitors" style="font-size: 1.75rem; font-weight: 800; color: #ef4444; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #3b82f6;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Search Appearances</h4>
                                <p id="gmb-search" style="font-size: 1.75rem; font-weight: 800; color: #3b82f6; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid var(--mint-green);">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Phone Calls</h4>
                                <p id="gmb-calls" style="font-size: 1.75rem; font-weight: 800; color: var(--mint-green); margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #8b5cf6;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Website Clicks</h4>
                                <p id="gmb-website" style="font-size: 1.75rem; font-weight: 800; color: #8b5cf6; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                            <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0; border-top: 3px solid #f59e0b;">
                                <h4 style="margin: 0; font-size: 0.75rem; color: var(--admin-text-muted); text-transform: uppercase;">Driving Directions</h4>
                                <p id="gmb-directions" style="font-size: 1.75rem; font-weight: 800; color: #f59e0b; margin: 0.4rem 0 0 0;">-</p>
                            </div>
                        </div>

                        <!-- GMB Timeline Chart -->
                        <div class="admin-card" style="margin-top: 1rem; padding: 1.25rem;">
                            <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: #fff;">GMB Local Interactions Timeline</h4>
                            <div style="height: 250px; position: relative;">
                                <canvas id="gmb-chart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- 4. Top Postal Codes Table -->
                    <div class="admin-card" style="margin-bottom: 2rem;">
                        <h4 style="margin: 0 0 1rem 0; font-size: 0.95rem; color: #fff;">📍 Top Visitor Locations by Postal / Zip Code</h4>
                        <div class="admin-table-wrap">
                            <table class="admin-table" style="font-size: 0.85rem;">
                                <thead>
                                    <tr>
                                        <th>Postal Code</th>
                                        <th>City / Region</th>
                                        <th style="text-align: right;">Active Users</th>
                                    </tr>
                                </thead>
                                <tbody id="ga-postal-tbody"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Google API Credentials & Configuration Card -->
                <div class="admin-card">
                    <h3 style="margin: 0 0 0.5rem 0; font-size: 1.1rem; color: #fff;"><i data-lucide="key" style="display: inline-block; vertical-align: middle; margin-right: 5px;"></i> Google API Connection Settings</h3>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">
                        Configure your GA4 Property ID, Google Search Console URL, and Google My Business Location ID.
                    </p>

                    <form action="{{ route('admin.parameters.update') }}" method="POST">
                        @csrf
                        <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.25rem;">
                            <div class="form-group">
                                <label for="google_analytics_property_id">GA4 Property ID</label>
                                <input type="text" id="google_analytics_property_id" name="google_analytics_property_id" class="form-control" value="{{ $site['google_analytics_property_id'] ?? '' }}" placeholder="e.g. 123456789">
                            </div>
                            <div class="form-group">
                                <label for="google_search_console_site_url">Search Console Site URL</label>
                                <input type="text" id="google_search_console_site_url" name="google_search_console_site_url" class="form-control" value="{{ $site['google_search_console_site_url'] ?? '' }}" placeholder="e.g. https://tenderfold.in">
                            </div>
                            <div class="form-group">
                                <label for="gmb_location_id">GMB Location ID</label>
                                <input type="text" id="gmb_location_id" name="gmb_location_id" class="form-control" value="{{ $site['gmb_location_id'] ?? '' }}" placeholder="e.g. locations/1234567890">
                            </div>
                        </div>

                        <div style="border-top: 1px solid var(--admin-border); margin: 1.5rem 0; padding-top: 1.5rem;">
                            <h4 style="font-size: 0.95rem; margin-bottom: 1rem; color: #fff;">Option A: Google Cloud OAuth Connection</h4>
                            @if(!empty($site['google_oauth_refresh_token']))
                                <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <span style="display: flex; align-items: center; justify-content: center; width: 32px; height: 32px; background: rgba(16, 185, 129, 0.2); color: #10b981; border-radius: 50%;">
                                            <i data-lucide="check-circle" style="width: 18px; height: 18px;"></i>
                                        </span>
                                        <div>
                                            <h4 style="margin: 0; color: #fff; font-size: 0.9rem;">Google Account Connected</h4>
                                            <p style="margin: 0; font-size: 0.78rem; color: var(--admin-text-muted);">Successfully authorized access for GA4, Search Console, and GMB.</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="document.getElementById('disconnect-google-form').submit();" class="btn-submit" style="background: #ef4444; border-color: #ef4444; padding: 0.4rem 1rem; font-size: 0.8rem; width: auto;">Disconnect Account</button>
                                </div>
                            @else
                                <div style="background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.2); padding: 1rem; border-radius: 8px; margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                                    <div>
                                        <h4 style="margin: 0; color: #fff; font-size: 0.9rem;">Connect Google Account (1-Click OAuth)</h4>
                                        <p style="margin: 0; font-size: 0.78rem; color: var(--admin-text-muted);">Authorize access to read your Analytics, Search Console, and GMB metrics automatically.</p>
                                    </div>
                                    <a href="{{ route('admin.google.auth') }}" class="btn-submit" style="background: #3b82f6; border-color: #3b82f6; text-decoration: none; display: inline-flex; align-items: center; padding: 0.4rem 1rem; font-size: 0.8rem; width: auto; gap: 5px;">
                                        <i data-lucide="link" style="width: 14px; height: 14px;"></i> Connect Google
                                    </a>
                                </div>
                            @endif

                            <div class="form-grid">
                                <div class="form-group">
                                    <label for="google_oauth_client_id">OAuth Client ID</label>
                                    <input type="text" id="google_oauth_client_id" name="google_oauth_client_id" class="form-control" value="{{ $site['google_oauth_client_id'] ?? '' }}" placeholder="e.g. 123456-xxx.apps.googleusercontent.com">
                                </div>
                                <div class="form-group">
                                    <label for="google_oauth_client_secret">OAuth Client Secret</label>
                                    <input type="password" id="google_oauth_client_secret" name="google_oauth_client_secret" class="form-control" value="{{ $site['google_oauth_client_secret'] ?? '' }}" placeholder="Enter Client Secret...">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" style="width: auto; padding: 0.6rem 1.5rem;">Save Google Credentials</button>
                    </form>
                    <form id="disconnect-google-form" action="{{ route('admin.google.disconnect') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>
            @endif

            @if($isSuper)
            <!-- Admin Users Tab Panel -->
            <div class="tab-panel @if($activeTab === 'users') active @endif" id="tab-users">
                <div class="admin-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 15px;">
                        <div>
                            <h2 style="margin: 0;"><i data-lucide="user" style="display: inline-block; vertical-align: middle; margin-right: 5px;"></i> Admin Users Management</h2>
                            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 5px 0 0 0;">
                                Add more administrators, change their email/password, and fine-tune their module permissions.
                            </p>
                        </div>
                        <button type="button" class="btn-submit" onclick="openCreateUserModal()" style="width: auto; padding: 0.65rem 1.25rem; font-size: 0.85rem; background-color: var(--mint-green); color: #0d1718; display: inline-flex; align-items: center; gap: 6px;">
                            <i data-lucide="user-plus" style="width: 16px; height: 16px;"></i> Create Admin User
                        </button>
                    </div>

                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 20%;">Name</th>
                                    <th style="width: 25%;">Email</th>
                                    <th style="width: 15%;">Role</th>
                                    <th style="width: 20%;">Module Permissions</th>
                                    <th style="width: 15%; text-align: center;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $usr)
                                    <tr>
                                        <td>{{ $usr->id }}</td>
                                        <td><strong>{{ $usr->name }}</strong></td>
                                        <td>{{ $usr->email }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $usr->role === 'superadmin' ? 'rgba(92, 203, 179, 0.15)' : 'rgba(255,255,255,0.06)' }}; color: {{ $usr->role === 'superadmin' ? 'var(--mint-green)' : '#fff' }}; border: 1px solid {{ $usr->role === 'superadmin' ? 'rgba(92, 203, 179, 0.3)' : 'rgba(255,255,255,0.1)' }}; padding: 3px 8px; border-radius: 4px; font-size: 0.75rem; text-transform: uppercase;">
                                                {{ $usr->role }}
                                            </span>
                                        </td>
                                        <td>
                                            @if($usr->role === 'superadmin' || (is_array($usr->permissions) && in_array('all', $usr->permissions)))
                                                <span style="color: var(--mint-green); font-size: 0.8rem;">All Modules (*)</span>
                                            @else
                                                <span style="font-size: 0.8rem; line-height: 1.4; display: block; color: var(--admin-text-muted);">
                                                    {{ implode(', ', array_map(function($p) use ($modules) { return $modules[$p] ?? $p; }, is_array($usr->permissions) ? $usr->permissions : (json_decode($usr->permissions ?? '[]', true) ?: []))) ?: 'None' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                                <button type="button" class="btn-action edit" title="Edit Admin" onclick="openEditUserModal('{{ json_encode($usr) }}')">
                                                    <i data-lucide="edit" style="width: 14px; height: 14px;"></i>
                                                </button>
                                                @if(Auth::id() !== $usr->id)
                                                    <form action="{{ route('admin.users.delete', $usr->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin user?');" style="display: inline;">
                                                        @csrf
                                                        <button type="submit" class="btn-action delete" title="Delete Admin">
                                                            <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif


            <!-- Internal System Notifications & Error Logs Tab Panel -->
            <div class="tab-panel @if($activeTab === 'internal_notifications') active @endif" id="tab-internal_notifications">
                <div class="admin-card">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                        <div>
                            <h2 style="margin: 0;"><i data-lucide="bell" style="vertical-align: middle; margin-right: 6px; color: var(--mint-green);"></i> Internal System Notifications & Error Logs</h2>
                            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin: 4px 0 0 0;">Automatic audit log of system errors, content edits, parameter changes, incoming leads, and live chats.</p>
                        </div>
                        <div style="display: flex; gap: 0.75rem; align-items: center;">
                            <button type="button" onclick="markAllNotificationsRead()" class="btn" style="background-color: rgba(92, 203, 179, 0.15); color: var(--mint-green); border: 1px solid var(--mint-green); padding: 0.5rem 1rem; font-size: 0.85rem; cursor: pointer;">
                                <i data-lucide="check-check" style="width: 14px; height: 14px; vertical-align: middle;"></i> Mark All Read
                            </button>
                            <button type="button" onclick="clearAllNotifications()" class="btn" style="background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; padding: 0.5rem 1rem; font-size: 0.85rem; cursor: pointer;">
                                <i data-lucide="trash-2" style="width: 14px; height: 14px; vertical-align: middle;"></i> Clear Log
                            </button>
                        </div>
                    </div>

                    <!-- Filter Tabs -->
                    <div style="display: flex; gap: 10px; margin-bottom: 1.25rem; border-bottom: 1px solid var(--admin-border); padding-bottom: 0.75rem;">
                        <button type="button" onclick="filterInternalNotifications('all')" class="btn-filter-notif active" id="notif-filter-all" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; background-color: var(--primary-teal); color: white; border: none; cursor: pointer;">All Alerts</button>
                        <button type="button" onclick="filterInternalNotifications('error')" class="btn-filter-notif" id="notif-filter-error" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; background-color: var(--admin-input-bg); color: #f87171; border: 1px solid var(--admin-border); cursor: pointer;">Errors</button>
                        <button type="button" onclick="filterInternalNotifications('edit')" class="btn-filter-notif" id="notif-filter-edit" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; background-color: var(--admin-input-bg); color: #60a5fa; border: 1px solid var(--admin-border); cursor: pointer;">Edits & Updates</button>
                        <button type="button" onclick="filterInternalNotifications('lead')" class="btn-filter-notif" id="notif-filter-lead" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; background-color: var(--admin-input-bg); color: #f59e0b; border: 1px solid var(--admin-border); cursor: pointer;">Leads</button>
                        <button type="button" onclick="filterInternalNotifications('chat')" class="btn-filter-notif" id="notif-filter-chat" style="padding: 6px 14px; font-size: 0.8rem; border-radius: 20px; background-color: var(--admin-input-bg); color: #34d399; border: 1px solid var(--admin-border); cursor: pointer;">Chats</button>
                    </div>

                    <!-- Notifications List Container -->
                    <div id="internal-notifications-table-wrap" style="max-height: 550px; overflow-y: auto;">
                        <div style="padding: 2.5rem; text-align: center; color: var(--admin-text-muted);">Loading system notifications...</div>
                    </div>
                </div>
            </div>

            @if($hasAccess('seo'))
            <!-- Web Notifications Management Tab Panel -->
            <div class="tab-panel @if($activeTab === 'notifications') active @endif" id="tab-notifications">
                <div class="form-grid" style="grid-template-columns: 2fr 1fr; gap: 1.5rem; align-items: start;">
                    
                    <!-- Broadcaster Form Card -->
                    <div class="admin-card">
                        <h2><i data-lucide="bell" style="display: inline-block; vertical-align: middle; margin-right: 5px;"></i> Push Notification Broadcaster</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Compose a notification to broadcast instantly to all subscribed visitor browsers. You can select an existing site entity to auto-fill the details.
                        </p>
                        
                        <form action="{{ route('admin.notifications.broadcast') }}" method="POST" id="broadcast-form">
                            @csrf
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="broadcast_type">Link Notification To:</label>
                                <select id="broadcast_type" name="type" class="form-control" onchange="handleBroadcastTypeChange(this.value)">
                                    <option value="custom">Custom Message (Manual URL)</option>
                                    <option value="blog">Blog Post</option>
                                    <option value="service">Service Page</option>
                                    <option value="cms_page">Custom CMS Page</option>
                                    <option value="landing">AI Landing Page</option>
                                    <option value="specialist">Specialist Doctor Profile</option>
                                </select>
                            </div>

                            <!-- Dynamic Selectors (initially hidden) -->
                            <div class="form-group" id="group-select-blog" style="display: none; margin-bottom: 1rem;">
                                <label for="select_blog">Select Blog Article:</label>
                                <select id="select_blog" class="form-control" onchange="autoFillFromEntity('blog', this.value)">
                                    <option value="">-- Choose Blog --</option>
                                    @foreach($blogs as $b)
                                        <option value="{{ $b->id }}" data-title="{{ $b->title }}" data-summary="{{ Str::limit(strip_tags($b->summary ?? ''), 150) }}" data-url="{{ route('blog.show', $b->slug) }}">{{ $b->title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="group-select-service" style="display: none; margin-bottom: 1rem;">
                                <label for="select_service">Select Service:</label>
                                <select id="select_service" class="form-control" onchange="autoFillFromEntity('service', this.value)">
                                    <option value="">-- Choose Service --</option>
                                    @foreach($services as $s)
                                        <option value="{{ $s->id }}" data-title="{{ $s->service_name }}" data-summary="{{ Str::limit(strip_tags($s->short_description ?? ''), 150) }}" data-url="{{ route('services.show', $s->slug) }}">{{ $s->service_name }}</option>
                                    @endforeach
                                     @foreach($cmsPages as $cp)
                                        <option value="{{ $cp->id }}" data-title="{{ $cp->title }}" data-summary="Learn more about Tenderfold services: {{ $cp->title }}" data-url="{{ url('/' . $cp->slug) }}">{{ $cp->title }}</option>
                                     @endforeach
                                </select>
                            </div>

                            <div class="form-group" id="group-select-landing" style="display: none; margin-bottom: 1rem;">
                                <label for="select_landing">Select AI Landing Page:</label>
                                <select id="select_landing" class="form-control" onchange="autoFillFromEntity('landing', this.value)">
                                    <option value="">-- Choose Landing Page --</option>
                                    @foreach($landingPages as $lp)
                                        <option value="{{ $lp->id }}" data-title="{{ $lp->target_title }}" data-summary="{{ Str::limit(strip_tags($lp->hero_subtitle ?? ''), 150) }}" data-url="{{ route('landing.show', $lp->slug) }}">{{ $lp->target_title }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Title, Message, URL -->
                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="broadcast_title">Notification Title</label>
                                <input type="text" id="broadcast_title" name="title" class="form-control" required placeholder="e.g. Free Laundry Pickup in Gurgaon!" oninput="updateLivePreview()">
                            </div>

                            <div class="form-group" style="margin-bottom: 1rem;">
                                <label for="broadcast_message">Message Body</label>
                                <textarea id="broadcast_message" name="message" class="form-control" rows="3" required placeholder="Enter description details (keep under 200 characters)..." oninput="updateLivePreview()"></textarea>
                            </div>

                            <div class="form-group" style="margin-bottom: 1.5rem;">
                                <label for="broadcast_url">Notification Redirect URL</label>
                                <input type="url" id="broadcast_url" name="url" class="form-control" placeholder="e.g. https://tenderfold.in/services" oninput="updateLivePreview()">
                            </div>

                            <button type="submit" class="btn-submit" style="background-color: var(--mint-green); color: #0d1718; font-weight: 700; width: 100%; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                <i data-lucide="send"></i> Broadcast Push Alert
                            </button>
                        </form>
                    </div>

                    <!-- Live Preview Card -->
                    <div class="admin-card" style="position: sticky; top: 20px;">
                        <h2>Live Preview</h2>
                        <p style="font-size: 0.8rem; color: var(--admin-text-muted); margin-bottom: 1rem; margin-top: -0.5rem;">
                            How this notification will display on visitor browsers:
                        </p>
                        
                        <div style="background: #1e293b; border: 1px solid rgba(255,255,255,0.1); border-radius: 8px; padding: 12px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.3); font-family: sans-serif; display: flex; gap: 12px; align-items: start; color: white;">
                            <div style="width: 36px; height: 36px; border-radius: 6px; background-color: var(--mint-green); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i data-lucide="bell" style="width: 18px; height: 18px; color: #0d1718;"></i>
                            </div>
                            <div style="flex-grow: 1; overflow: hidden;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2px;">
                                    <strong id="preview-title" style="font-size: 0.85rem; font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">Tenderfold</strong>
                                    <span style="font-size: 0.65rem; color: #94a3b8;">now</span>
                                </div>
                                <p id="preview-message" style="margin: 0; font-size: 0.75rem; color: #cbd5e1; line-height: 1.3; word-break: break-word;">Waiting for input details...</p>
                                <span id="preview-url" style="display: block; font-size: 0.65rem; color: var(--mint-green); margin-top: 4px; text-decoration: underline; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 180px;">https://tenderfold.in</span>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Sent Notifications Log -->
                <div class="admin-card" style="margin-top: 2rem;">
                    <h2>Sent Notifications History</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">ID</th>
                                    <th style="width: 20%;">Title</th>
                                    <th style="width: 40%;">Message Details</th>
                                    <th style="width: 20%;">Redirect URL</th>
                                    <th style="width: 15%;">Broadcast Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($notificationsLog as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td><strong>{{ $log->title }}</strong></td>
                                        <td>{{ $log->message }}</td>
                                        <td>
                                            @if($log->url)
                                                <a href="{{ $log->url }}" target="_blank" style="color: var(--mint-green); text-decoration: underline; font-size: 0.8rem;">{{ Str::limit($log->url, 40) }}</a>
                                            @else
                                                <span style="color: var(--admin-text-muted); font-size: 0.8rem;">None</span>
                                            @endif
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($log->created_at)->format('d M Y, h:i A') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" style="text-align: center; color: var(--admin-text-muted);">No broadcast alerts sent yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div style="margin-top: 1rem;">
                        {{ $notificationsLog->links() }}
                    </div>
                </div>
            </div>
            @endif
        </div> <!-- end .admin-content -->
    </div> <!-- end .main-panel -->

    <!-- AI Toast Notification Container -->
    <div id="ai-toast-container" style="position: fixed; bottom: 20px; right: 20px; z-index: 999999; display: flex; flex-direction: column; gap: 10px;"></div>

    <!-- AI Landing Page Generator Modal -->
    <div class="ai-modal-overlay" id="ai-landing-modal">
        <div class="ai-modal-card">
            <button class="ai-modal-close" onclick="closeAiModal('landing')">&times;</button>
            <h3><i data-lucide="sparkles"></i> AI Landing Page Generator</h3>
            
            <input type="hidden" id="ai_landing_id" value="">
            <div id="ai-landing-form-wrapper">
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label>Page Title</label>
                    <input type="text" id="ai_landing_target_title" class="form-control" readonly style="opacity: 0.7;">
                </div>
                <div class="form-group">
                    <label for="ai_landing_prompt">Prompt / Describe the topic for this Landing Page</label>
                    <textarea id="ai_landing_prompt" class="form-control" rows="4" placeholder="e.g. Dedicated landing page for bridal couture and silk saree dry cleaning in Gurgaon with doorstep pickup and delicate hand finishing..."></textarea>
                </div>
                <div style="display: flex; gap: 10px; margin-top: 1.5rem;">
                    <button type="button" class="btn-ai-sparkle" onclick="generateAiLandingPage()"><i data-lucide="sparkles"></i> Generate Content</button>
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff; width: auto;" onclick="closeAiModal('landing')">Cancel</button>
                </div>
            </div>
            
            <div class="ai-loader-container" id="ai-landing-loader">
                <div class="ai-sparkle-loader"></div>
                <p class="ai-loader-text">Gemini is designing and writing your landing page...</p>
                <p class="ai-loader-subtext">This can take up to 10-15 seconds.</p>
            </div>
        </div>
    </div>

    <!-- AI Blog Generator Modal -->
    <div class="ai-modal-overlay" id="ai-blog-modal">
        <div class="ai-modal-card">
            <button class="ai-modal-close" onclick="closeAiModal('blog')">&times;</button>
            <h3><i data-lucide="sparkles"></i> AI Blog Article Generator</h3>
            
            <div id="ai-blog-form-wrapper">
                <div class="form-group">
                    <label for="ai_blog_prompt">What should the blog article be about?</label>
                    <textarea id="ai_blog_prompt" class="form-control" rows="4" placeholder="e.g. Benefits of physiotherapy for chronic joint pain, recovering post knee surgery, or preventing back pain at a desk job..."></textarea>

                </div>
                
                <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="ai_blog_tone">Writing Tone</label>
                        <select id="ai_blog_tone" class="form-control">
                            <option value="Professional & Informative">Professional & Informative</option>
                            <option value="Conversational & Friendly">Conversational & Friendly</option>
                            <option value="Persuasive & Engaging">Persuasive & Engaging</option>
                            <option value="Simple & Clear">Simple & Clear</option>
                        </select>
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; margin-top: 1.5rem;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; cursor: pointer; font-size: 0.85rem;">
                            <input type="checkbox" id="ai_blog_gen_image" value="1" checked style="width: 16px; height: 16px; accent-color: var(--mint-green);">
                            Generate Cover Image
                        </label>
                    </div>
                </div>
                
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="closeAiModal('blog')">Cancel</button>
                    <button type="button" class="btn-ai-sparkle" onclick="generateAiBlog()"><i data-lucide="sparkles"></i> Generate Draft</button>
                </div>
            </div>
            
            <div class="ai-loader-container" id="ai-blog-loader">
                <div class="ai-sparkle-loader"></div>
                <p class="ai-loader-text">Gemini is writing your article...</p>
                <p class="ai-loader-subtext">This can take up to 20-30 seconds depending on image generation.</p>
            </div>
        </div>
    </div>

    <!-- GMB Sharing Modal -->
    <div class="ai-modal-overlay" id="gmb-share-modal">
        <div class="ai-modal-card">
            <button class="ai-modal-close" onclick="closeGmbModal()">&times;</button>
            <h3><i data-lucide="share-2" style="color: #4285F4; display: inline-block; vertical-align: middle;"></i> Share to Google Business Profile</h3>
            
            <div id="gmb-form-wrapper">
                <input type="hidden" id="gmb_post_type">
                <input type="hidden" id="gmb_post_id">
                <input type="hidden" id="gmb_target_url">
                
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label><strong>Post Text Copy (AI Generated or Custom)</strong></label>
                    <textarea id="gmb_post_text" class="form-control" rows="8" placeholder="Drafting your GMB post..."></textarea>
                </div>
                
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label><strong>Action Button (CTA) Target Link</strong></label>
                    <input type="text" id="gmb_post_cta_url" class="form-control" readonly style="background-color: var(--admin-input-bg); opacity: 0.8;">
                    <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                        This CTA button redirects users to the page above with the label "Learn More".
                    </span>
                </div>
                
                <div style="background: rgba(232, 92, 36, 0.1); border: 1px solid rgba(232, 92, 36, 0.3); border-radius: var(--radius-sm); padding: 12px; margin-bottom: 1.5rem; font-size: 0.85rem;" id="gmb-manual-notice">
                    <strong style="color: var(--accent-orange);"><i data-lucide="info" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i> Tip:</strong>
                    Copy the draft and use the "Open GMB Dashboard" button to publish it manually on Google Business Profile if GBP API is not configured.
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <div>
                        <button type="button" class="btn-ai-sparkle" onclick="generateGmbDraft()" id="gmb-draft-btn" style="padding: 0.5rem 1rem; font-size: 0.85rem; width: auto;">
                            <i data-lucide="sparkles"></i> Draft with AI
                        </button>
                    </div>
                    <div style="display: flex; gap: 10px;">
                        <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff; width: auto;" onclick="closeGmbModal()">Cancel</button>
                        
                        @if(!empty($site['gmb_location_id']) && !empty($site['gmb_access_token']))
                            <button type="button" class="btn-submit" style="background-color: #4285F4; color: #fff; width: auto;" onclick="publishGmbPostDirect()" id="gmb-publish-btn">
                                <i data-lucide="check"></i> Publish Now
                            </button>
                        @endif
                        
                        <button type="button" class="btn-submit" style="background-color: var(--mint-green); color: var(--primary-teal-dark); width: auto;" onclick="copyAndOpenGmb()" id="gmb-manual-btn">
                            <i data-lucide="external-link"></i> Copy & Open Dashboard
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="ai-loader-container" id="gmb-loader" style="display: none;">
                <div class="ai-sparkle-loader" style="margin: 0 auto 1.5rem auto;"></div>
                <p class="ai-loader-text" id="gmb-loader-text">Drafting post with Gemini AI...</p>
            </div>
        </div>
    </div>

    <!-- AI Service Page Generator Modal -->
    <div class="ai-modal-overlay" id="ai-service-modal">
        <div class="ai-modal-card">
            <button class="ai-modal-close" onclick="closeAiModal('service')">&times;</button>
            <h3><i data-lucide="sparkles"></i> AI Service Page Generator</h3>
            
            <div id="ai-service-form-wrapper">
                <div class="form-group">
                    <label for="ai_service_prompt">Describe the Service Page you want to create:</label>
                    <textarea id="ai_service_prompt" class="form-control" rows="4" placeholder="e.g. Robotic Joint Replacement: covering clinical precision, recovery timelines, hospital stay durations, and patient consulting hours."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="ai_service_tone">Writing Tone</label>
                    <select id="ai_service_tone" class="form-control">
                        <option value="Professional & Clinical">Professional & Clinical</option>
                        <option value="Informative & Helpful">Informative & Helpful</option>
                        <option value="Persuasive & Patient-Oriented">Persuasive & Patient-Oriented</option>
                    </select>
                </div>

                
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="closeAiModal('service')">Cancel</button>
                    <button type="button" class="btn-ai-sparkle" onclick="generateAiService()"><i data-lucide="sparkles"></i> Generate Service</button>
                </div>
            </div>
            
            <div class="ai-loader-container" id="ai-service-loader">
                <div class="ai-sparkle-loader"></div>
                <p class="ai-loader-text">Gemini is generating service page details...</p>
                <p class="ai-loader-subtext">Structuring Hero content, eligibility criteria, required documents, and FAQs.</p>
            </div>
        </div>
    </div>

    <!-- AI Picture Generator Modal -->
    <div class="ai-modal-overlay" id="ai-image-modal">
        <div class="ai-modal-card">
            <button class="ai-modal-close" onclick="closeAiModal('image')">&times;</button>
            <h3><i data-lucide="sparkles"></i> AI Picture Generator (Imagen 3)</h3>
            
            <div id="ai-image-form-wrapper">
                <div class="form-group">
                    <label for="ai_image_prompt">Describe the image you want to generate:</label>
                    <textarea id="ai_image_prompt" class="form-control" rows="4" placeholder="e.g. A realistic photo of a young couple receiving keys to their new home, warm lighting, high detail..."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="ai_image_aspect_ratio">Aspect Ratio</label>
                    <select id="ai_image_aspect_ratio" class="form-control">
                        <option value="1:1">1:1 Square (Default)</option>
                        <option value="16:9">16:9 Landscape (Slider / Blog cover)</option>
                        <option value="4:3">4:3 Standard</option>
                    </select>
                </div>
                
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="closeAiModal('image')">Cancel</button>
                    <button type="button" class="btn-ai-sparkle" onclick="generateAiImage()"><i data-lucide="sparkles"></i> Generate Picture</button>
                </div>
            </div>
            
            <div class="ai-loader-container" id="ai-image-loader">
                <div class="ai-sparkle-loader"></div>
                <p class="ai-loader-text">Imagen is rendering your picture...</p>
                <p class="ai-loader-subtext">This will take 10-15 seconds. Please do not close this modal.</p>
            </div>

            <!-- Preview box of generated image -->
            <div class="ai-img-preview-box" id="ai-image-preview-container">
                <img id="ai-image-preview-img" src="" alt="Generated Preview">
                <div class="info">
                    <span style="font-size: 0.8rem; color: var(--admin-text-muted);" id="ai-image-status-text">Saved to Media Library!</span>
                    <button type="button" class="btn-submit" style="padding: 5px 12px; font-size: 0.8rem;" id="ai-image-copy-btn">
                        <i data-lucide="copy" style="width: 14px; height: 14px; display: inline; vertical-align: middle;"></i> Copy URL
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- AI Slider Generator Modal -->
    <div class="ai-modal-overlay" id="ai-slider-modal">
        <div class="ai-modal-card">
            <button class="ai-modal-close" onclick="closeAiModal('slider')">&times;</button>
            <h3><i data-lucide="sparkles"></i> Generate Homepage Slide with AI</h3>
            <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem;">
                Enter a topic or offer, and Gemini will generate a compelling heading, subheading, call to action text, and a matched background image for the homepage slider.
            </p>
            <div id="ai-slider-form-wrapper">
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label for="ai_slider_prompt">What is the slider slide about? (e.g. Free doorstep laundry pickup, 20% off dry cleaning)</label>
                    <textarea id="ai_slider_prompt" class="form-control" rows="3" placeholder="Describe the offer, fabric care service, or seasonal campaign for this slide..."></textarea>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; color: white;">
                        <input type="checkbox" id="ai_slider_gen_image" value="1" checked style="width: 16px; height: 16px; accent-color: var(--mint-green);">
                        Generate Background Image using AI
                    </label>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff; width: auto;" onclick="closeAiModal('slider')">Cancel</button>
                    <button type="button" class="btn-ai-sparkle" style="width: auto;" onclick="generateAiSlider()"><i data-lucide="sparkles"></i> Generate Slide</button>
                </div>
            </div>
            <div class="ai-loader-container" id="ai-slider-loader" style="display: none;">
                <div class="ai-sparkle-loader"></div>
                <p class="ai-loader-text">Gemini is designing and writing your slide...</p>
                <p class="ai-loader-subtext">This will take 10-15 seconds. Please do not close this modal.</p>
            </div>
        </div>
    </div>

    <!-- AI CMS Page Generator Modal -->
    <div class="ai-modal-overlay" id="ai-cms-modal">
        <div class="ai-modal-card">
            <button class="ai-modal-close" onclick="closeAiModal('cms')">&times;</button>
            <h3><i data-lucide="sparkles"></i> AI CMS Page Generator</h3>
            
            <div id="ai-cms-form-wrapper">
                <div class="form-group">
                    <label for="ai_cms_prompt">Describe the CMS Page topic/content:</label>
                    <textarea id="ai_cms_prompt" class="form-control" rows="4" placeholder="e.g. Fabric Care Guidelines & Stain Removal Policy for Tenderfold Laundry Services, Gurgaon..."></textarea>
                </div>
                
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="closeAiModal('cms')">Cancel</button>
                    <button type="button" class="btn-ai-sparkle" onclick="generateAiCmsPage()"><i data-lucide="sparkles"></i> Generate Page</button>
                </div>
            </div>
            
            <div class="ai-loader-container" id="ai-cms-loader" style="display: none;">
                <div class="ai-sparkle-loader"></div>
                <p class="ai-loader-text">Gemini is creating CMS page content...</p>
                <p class="ai-loader-subtext">Drafting page title, slug, meta data, and HTML article layout.</p>
            </div>
        </div>
    </div>

    <!-- AI Specialist Profile Generator Modal -->
    <div class="ai-modal-overlay" id="ai-specialist-modal">
        <div class="ai-modal-card">
            <button class="ai-modal-close" onclick="closeAiModal('specialist')">&times;</button>
            <h3><i data-lucide="sparkles"></i> AI Specialist Profile Generator</h3>
            
            <div id="ai-specialist-form-wrapper">
                <div class="form-group">
                    <label for="ai_specialist_prompt">Doctor/Specialist Name & Specialty:</label>
                    <textarea id="ai_specialist_prompt" class="form-control" rows="4" placeholder="e.g. Dr. Sumit Diwan, Lead Knee & Joint Replacement Surgeon with 15+ years surgical experience in Pune..."></textarea>
                </div>
                
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="closeAiModal('specialist')">Cancel</button>
                    <button type="button" class="btn-ai-sparkle" onclick="generateAiSpecialist()"><i data-lucide="sparkles"></i> Generate Profile</button>
                </div>
            </div>
            
            <div class="ai-loader-container" id="ai-specialist-loader" style="display: none;">
                <div class="ai-sparkle-loader"></div>
                <p class="ai-loader-text">Gemini is writing specialist profile...</p>
                <p class="ai-loader-subtext">Structuring medical credentials, experience, and biography.</p>
            </div>
        </div>
    </div>


    <!-- Add CKEditor CSS custom overrides -->
    <style>
        .ck-editor__editable {
            color: #111111 !important;
            min-height: 250px;
        }
    </style>

    <!-- Lucide CDN & CKEditor CDN -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- html2pdf.js CDN for PDF Download -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        lucide.createIcons();

        // Global variables declared at the top to prevent Temporal Dead Zone (TDZ) ReferenceErrors
        let cloudflareChartInstance = null;
        let gaChartInstance = null;
        let gscChartInstance = null;
        let gmbChartInstance = null;
        let currentAdminSessionId = null;
        let adminSessionsPoll = null;
        let adminMessagesPoll = null;
        let lastSessionsJson = '';
        let lastMessagesJson = '';


        function syncLocalImagesToR2(btn) {
            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="ai-toast-spinner" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 5px;"></span> Syncing...';

            showToast('Syncing local backups to your Cloudflare R2 bucket...', 'info');

            fetch("{{ route('admin.media.sync') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                if (res.success) {
                    showToast(res.message, 'success');
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    showToast(res.message || 'Sync failed.', 'error');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                showToast('Sync failed: ' + err.message, 'error');
            });
        }

        function pushLocalDatabaseToLive(btn) {
            const liveUrlInput = document.getElementById('live_site_url');
            const liveUrl = liveUrlInput ? liveUrlInput.value.trim() : 'https://tenderfold.in';
            
            if (!liveUrl) {
                showToast('Please enter a valid Live Site Base URL first.', 'error');
                return;
            }

            if (!confirm('Are you sure you want to push your local database content to the live server? This will overwrite the live server\'s Settings, Slides, Blogs, Services, and Page Contents with your local ones.')) {
                return;
            }

            const originalHtml = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="ai-toast-spinner" style="width: 14px; height: 14px; display: inline-block; vertical-align: middle; margin-right: 5px;"></span> Pushing...';

            showToast('Gathering local records and pushing to live server...', 'info');

            fetch("{{ route('admin.database.push-to-live') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    live_site_url: liveUrl
                })
            })
            .then(res => res.json())
            .then(res => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                if (res.success) {
                    showToast(res.message, 'success');
                } else {
                    showToast(res.message || 'Push failed.', 'error');
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = originalHtml;
                showToast('Push network error: ' + err.message, 'error');
            });
        }

        function fetchCloudflareAnalytics() {
            const loader = document.getElementById('analytics-loader');
            const errorDiv = document.getElementById('analytics-error');
            const errorMsg = document.getElementById('analytics-error-msg');
            const dashboard = document.getElementById('analytics-dashboard');

            if (!loader) return;

            loader.style.display = 'flex';
            errorDiv.style.display = 'none';
            dashboard.style.display = 'none';

            fetch("{{ route('admin.cloudflare.analytics') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                }
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const data = res.data;
                    
                    let totalPageviews = 0;
                    let totalRequests = 0;
                    let totalBytes = 0;
                    let totalCachedRequests = 0;
                    
                    const labels = [];
                    const pageviewsSeries = [];
                    const requestsSeries = [];

                    data.forEach(day => {
                        const sum = day.sum;
                        totalPageviews += parseInt(sum.pageViews || 0);
                        totalRequests += parseInt(sum.requests || 0);
                        totalBytes += parseInt(sum.bytes || 0);
                        totalCachedRequests += parseInt(sum.cachedRequests || 0);

                        // Format date nicely (YYYY-MM-DD to DD MMM)
                        const dateObj = new Date(day.dimensions.date);
                        const formattedDate = dateObj.toLocaleDateString('en-US', { day: 'numeric', month: 'short' });

                        labels.push(formattedDate);
                        pageviewsSeries.push(sum.pageViews || 0);
                        requestsSeries.push(sum.requests || 0);
                    });

                    document.getElementById('stat-pageviews').innerText = totalPageviews.toLocaleString();
                    document.getElementById('stat-requests').innerText = totalRequests.toLocaleString();
                    
                    const cacheRate = totalRequests > 0 ? Math.round((totalCachedRequests / totalRequests) * 100) : 0;
                    document.getElementById('stat-cache').innerText = cacheRate + '%';
                    
                    const bandwidthMb = (totalBytes / (1024 * 1024)).toFixed(1);
                    document.getElementById('stat-bandwidth').innerText = parseFloat(bandwidthMb).toLocaleString() + ' MB';

                    loader.style.display = 'none';
                    dashboard.style.display = 'block';

                    // Render Chart.js
                    const chartCanvas = document.getElementById('cloudflareChart');
                    if (chartCanvas) {
                        const ctx = chartCanvas.getContext('2d');
                        if (cloudflareChartInstance) {
                            cloudflareChartInstance.destroy();
                        }

                        cloudflareChartInstance = new Chart(ctx, {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [
                                    {
                                        label: 'Pageviews',
                                        data: pageviewsSeries,
                                        borderColor: '#5ccbb3',
                                        backgroundColor: 'rgba(92, 203, 179, 0.15)',
                                        tension: 0.3,
                                        fill: true,
                                        borderWidth: 3,
                                        pointBackgroundColor: '#5ccbb3',
                                        pointBorderColor: '#ffffff',
                                        pointHoverRadius: 6
                                    },
                                    {
                                        label: 'Requests',
                                        data: requestsSeries,
                                        borderColor: '#3b82f6',
                                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                        tension: 0.3,
                                        fill: true,
                                        borderWidth: 2,
                                        borderDash: [5, 5],
                                        pointBackgroundColor: '#3b82f6',
                                        pointBorderColor: '#ffffff',
                                        pointHoverRadius: 5
                                    }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        labels: {
                                            color: '#ffffff',
                                            font: { family: 'Outfit', size: 12 }
                                        }
                                    },
                                    tooltip: {
                                        mode: 'index',
                                        intersect: false,
                                        bodyFont: { family: 'Outfit' },
                                        titleFont: { family: 'Outfit', weight: 'bold' }
                                    }
                                },
                                scales: {
                                    x: {
                                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                        ticks: { color: 'rgba(255, 255, 255, 0.6)', font: { family: 'Outfit' } }
                                    },
                                    y: {
                                        grid: { color: 'rgba(255, 255, 255, 0.05)' },
                                        ticks: { color: 'rgba(255, 255, 255, 0.6)', font: { family: 'Outfit' } }
                                    }
                                }
                            }
                        });
                    }
                } else {
                    loader.style.display = 'none';
                    errorDiv.style.display = 'block';
                    errorMsg.innerText = res.error || 'Failed to retrieve Cloudflare analytics data.';
                }
            })
            .catch(err => {
                loader.style.display = 'none';
                errorDiv.style.display = 'block';
                errorMsg.innerText = 'Network error fetching Cloudflare analytics: ' + err.message;
            });
        }

        function fetchGoogleAnalytics(dateRange = 'last_7_days') {
            const loader = document.getElementById('google-analytics-loader');
            const dashboard = document.getElementById('google-analytics-dashboard');
            const demoBanner = document.getElementById('google-analytics-demo-banner');

            if (!loader) return;

            loader.style.display = 'flex';
            dashboard.style.display = 'none';
            if (demoBanner) demoBanner.style.display = 'none';

            fetch("{{ route('admin.google.analytics') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ date_range: dateRange })
            })
            .then(res => res.json())
            .then(res => {
                loader.style.display = 'none';
                dashboard.style.display = 'block';

                if (res.success && res.data) {
                    const data = res.data;

                    if (demoBanner) {
                        demoBanner.style.display = res.is_demo ? 'flex' : 'none';
                    }

                    // 1. GA4 Overview
                    const ga = data.analytics;
                    if (ga) {
                        document.getElementById('ga-sessions').innerText = (ga.sessions || 0).toLocaleString();
                        document.getElementById('ga-users').innerText = (ga.unique_users || 0).toLocaleString();
                        document.getElementById('ga-returning').innerText = (ga.repeated_users || 0).toLocaleString();
                        document.getElementById('ga-bounce').innerText = parseFloat(ga.bounce_rate || 0).toFixed(1) + '%';
                        document.getElementById('ga-time').innerText = ga.time_on_site || '0m 0s';
                        document.getElementById('ga-pages-per-session').innerText = parseFloat(ga.avg_pages_per_session || 0).toFixed(1);

                        // Render GA4 Chart
                        const gaCanvas = document.getElementById('ga-chart');
                        if (gaCanvas && ga.daily) {
                            const ctx = gaCanvas.getContext('2d');
                            if (gaChartInstance) gaChartInstance.destroy();
                            gaChartInstance = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: ga.daily.map(d => d.date),
                                    datasets: [
                                        {
                                            label: 'Sessions',
                                            data: ga.daily.map(d => d.sessions),
                                            borderColor: '#5ccbb3',
                                            backgroundColor: 'rgba(92, 203, 179, 0.1)',
                                            fill: true,
                                            tension: 0.3
                                        },
                                        {
                                            label: 'Active Users',
                                            data: ga.daily.map(d => d.users),
                                            borderColor: '#3b82f6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            fill: true,
                                            tension: 0.3
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { labels: { color: '#ffffff' } } },
                                    scales: {
                                        x: { ticks: { color: '#888888' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                                        y: { ticks: { color: '#888888' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                                    }
                                }
                            });
                        }
                    }

                    // 2. Google Search Console (GSC)
                    const gsc = data.search_console;
                    if (gsc) {
                        document.getElementById('gsc-clicks').innerText = (gsc.clicks || 0).toLocaleString();
                        document.getElementById('gsc-impressions').innerText = (gsc.impressions || 0).toLocaleString();
                        document.getElementById('gsc-ctr').innerText = parseFloat(gsc.ctr || 0).toFixed(2) + '%';
                        document.getElementById('gsc-position').innerText = parseFloat(gsc.position || 0).toFixed(1);

                        // Top Keywords Table
                        const kwTbody = document.getElementById('gsc-keywords-tbody');
                        if (kwTbody) {
                            kwTbody.innerHTML = '';
                            if (gsc.keywords && gsc.keywords.length > 0) {
                                gsc.keywords.forEach(kw => {
                                    kwTbody.innerHTML += `
                                        <tr>
                                            <td><strong>${kw.keyword}</strong></td>
                                            <td style="text-align: right;">${kw.clicks.toLocaleString()}</td>
                                            <td style="text-align: right;">${kw.impressions.toLocaleString()}</td>
                                            <td style="text-align: right; color: var(--mint-green);">${kw.ctr}%</td>
                                            <td style="text-align: right; color: #60a5fa;">${kw.position}</td>
                                        </tr>
                                    `;
                                });
                            } else {
                                kwTbody.innerHTML = '<tr><td colspan="5" style="text-align: center; color: var(--admin-text-muted);">No keyword data available.</td></tr>';
                            }
                        }

                        // Top Pages Table
                        const pgTbody = document.getElementById('gsc-pages-tbody');
                        if (pgTbody) {
                            pgTbody.innerHTML = '';
                            if (gsc.pages && gsc.pages.length > 0) {
                                gsc.pages.forEach(pg => {
                                    pgTbody.innerHTML += `
                                        <tr>
                                            <td><span style="font-size: 0.82rem; word-break: break-all; color: var(--admin-text-muted);">${pg.page}</span></td>
                                            <td style="text-align: right;">${pg.clicks.toLocaleString()}</td>
                                            <td style="text-align: right;">${pg.impressions.toLocaleString()}</td>
                                            <td style="text-align: right; color: var(--mint-green);">${pg.ctr}%</td>
                                        </tr>
                                    `;
                                });
                            } else {
                                pgTbody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: var(--admin-text-muted);">No page data available.</td></tr>';
                            }
                        }

                        // GSC Chart
                        const gscCanvas = document.getElementById('gsc-chart');
                        if (gscCanvas && gsc.daily) {
                            const ctx = gscCanvas.getContext('2d');
                            if (gscChartInstance) gscChartInstance.destroy();
                            gscChartInstance = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: gsc.daily.map(d => d.date),
                                    datasets: [
                                        {
                                            label: 'Clicks',
                                            data: gsc.daily.map(d => d.clicks),
                                            borderColor: '#3b82f6',
                                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                            fill: true,
                                            tension: 0.3
                                        },
                                        {
                                            label: 'Impressions',
                                            data: gsc.daily.map(d => d.impressions),
                                            borderColor: '#8b5cf6',
                                            backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                            fill: true,
                                            tension: 0.3
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { labels: { color: '#ffffff' } } },
                                    scales: {
                                        x: { ticks: { color: '#888888' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                                        y: { ticks: { color: '#888888' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                                    }
                                }
                            });
                        }
                    }

                    // 3. Google My Business (GMB)
                    const gmb = data.my_business;
                    if (gmb) {
                        document.getElementById('gmb-visitors').innerText = (gmb.visitors || 0).toLocaleString();
                        document.getElementById('gmb-search').innerText = (gmb.search_appearance || 0).toLocaleString();
                        document.getElementById('gmb-calls').innerText = (gmb.calls || 0).toLocaleString();
                        document.getElementById('gmb-website').innerText = (gmb.website_visits || 0).toLocaleString();
                        document.getElementById('gmb-directions').innerText = (gmb.directions || 0).toLocaleString();

                        // GMB Chart
                        const gmbCanvas = document.getElementById('gmb-chart');
                        if (gmbCanvas && gmb.daily) {
                            const ctx = gmbCanvas.getContext('2d');
                            if (gmbChartInstance) gmbChartInstance.destroy();
                            gmbChartInstance = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: gmb.daily.map(d => d.date),
                                    datasets: [
                                        { label: 'Views', data: gmb.daily.map(d => d.visitors), backgroundColor: '#ef4444' },
                                        { label: 'Calls', data: gmb.daily.map(d => d.calls), backgroundColor: '#5ccbb3' },
                                        { label: 'Website Visits', data: gmb.daily.map(d => d.website_visits), backgroundColor: '#8b5cf6' },
                                        { label: 'Directions', data: gmb.daily.map(d => d.directions), backgroundColor: '#f59e0b' }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: { legend: { labels: { color: '#ffffff' } } },
                                    scales: {
                                        x: { ticks: { color: '#888888' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                                        y: { ticks: { color: '#888888' }, grid: { color: 'rgba(255,255,255,0.05)' } }
                                    }
                                }
                            });
                        }
                    }

                    // 4. Postal Codes
                    const postalTbody = document.getElementById('ga-postal-tbody');
                    if (postalTbody) {
                        postalTbody.innerHTML = '';
                        if (data.postal_codes && data.postal_codes.length > 0) {
                            data.postal_codes.forEach(p => {
                                postalTbody.innerHTML += `
                                    <tr>
                                        <td><strong>${p.zip}</strong></td>
                                        <td>${p.city}</td>
                                        <td style="text-align: right; color: var(--mint-green); font-weight: 700;">${p.users.toLocaleString()}</td>
                                    </tr>
                                `;
                            });
                        } else {
                            postalTbody.innerHTML = '<tr><td colspan="3" style="text-align: center; color: var(--admin-text-muted);">No location data.</td></tr>';
                        }
                    }
                }
            })
            .catch(err => {
                loader.style.display = 'none';
                dashboard.style.display = 'block';
            });
        }

        // Switch Tabs logic (Route-based redirect)
        function switchTab(tabId) {
            window.location.href = "{{ url('/admin') }}/" + tabId;
        }

        // Toggle Sidebar drawer on mobile or collapse on desktop
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar) {
                if (window.innerWidth >= 992) {
                    document.body.classList.toggle('sidebar-collapsed');
                } else {
                    sidebar.classList.toggle('show');
                    if (backdrop) {
                        backdrop.classList.toggle('show');
                    }
                }
            }
        }

        // Initialize active tab from URL segment or activeTab passed from controller
        function initActiveTab() {
            const activeTab = "{{ $activeTab }}";

            // Sync layout classes with the active tab
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
            document.querySelectorAll('.tab-btn, .mobile-tab-btn').forEach(b => b.classList.remove('active'));

            const targetPanel = document.getElementById('tab-' + activeTab);
            const targetBtn = document.getElementById('btn-' + activeTab);
            const mobileBtn = document.getElementById('m-btn-' + activeTab);

            if (targetPanel) targetPanel.classList.add('active');
            if (targetBtn) targetBtn.classList.add('active');
            if (mobileBtn) {
                mobileBtn.classList.add('active');
                mobileBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }

            const adminContent = document.querySelector('.admin-content');
            if (adminContent) {
                adminContent.scrollTop = 0;
            }

            // Save to localStorage as fallback
            localStorage.setItem('active_tab', activeTab);

            // Manage analytics pageviews loading
            if (activeTab === 'analytics') {
                if (typeof fetchCloudflareAnalytics === 'function') {
                    fetchCloudflareAnalytics();
                }
                if (typeof fetchGoogleAnalytics === 'function') {
                    fetchGoogleAnalytics();
                }
            }

            // Manage chat pollings
            if (activeTab === 'livechat') {
                if (typeof loadAdminChatSessions === 'function') {
                    loadAdminChatSessions();
                }
            } else {
                if (typeof adminSessionsPoll !== 'undefined') clearInterval(adminSessionsPoll);
                if (typeof adminMessagesPoll !== 'undefined') clearInterval(adminMessagesPoll);
            }


        }

        // Initialize defensively on page load or immediately if DOM is ready
        if (document.readyState === 'loading') {
            window.addEventListener('DOMContentLoaded', initActiveTab);
        } else {
            initActiveTab();
        }

        // SEO Recommendations Switch Result Tab
        function switchSeoResultTab(tabId) {
            // Remove active class from all buttons and add background styling
            document.querySelectorAll('[id^="seo-tab-btn-"]').forEach(btn => {
                btn.classList.remove('active');
                btn.style.backgroundColor = 'var(--admin-input-bg)';
                btn.style.color = '#fff';
            });
            // Hide all subpanels
            document.querySelectorAll('.seo-result-panel').forEach(panel => {
                panel.style.display = 'none';
            });
            
            // Activate the clicked button
            const activeBtn = document.getElementById('seo-tab-btn-' + tabId);
            if (activeBtn) {
                activeBtn.classList.add('active');
                activeBtn.style.backgroundColor = 'var(--mint-green)';
                activeBtn.style.color = 'var(--primary-teal-dark)';
            }
            // Show the clicked panel
            const activePanel = document.getElementById('seo-panel-' + tabId);
            if (activePanel) {
                activePanel.style.display = activePanel.classList.contains('form-grid') ? 'grid' : 'block';
            }
        }

        let latestSeoData = null;

        // AJAX Generate SEO Strategy
        function generateSeoRecommendations() {
            const keywords = document.getElementById('seo_keywords').value.trim();
            if (!keywords) {
                alert('Please enter at least one target keyword.');
                return;
            }

            const loader = document.getElementById('seo-loader');
            const results = document.getElementById('seo-results');
            loader.style.display = 'block';
            results.style.display = 'none';
            loader.scrollIntoView({ behavior: 'smooth' });

            fetch("{{ route('admin.ai.seo-recommendations') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    keywords: keywords
                })
            })
            .then(res => res.json())
            .then(res => {
                loader.style.display = 'none';
                if (res.success && res.data) {
                    const data = res.data;
                    latestSeoData = data;
                    
                    // Render 1. On-Site Content Table
                    const contentTbody = document.getElementById('seo-table-content');
                    contentTbody.innerHTML = '';
                    if (data.pages_blogs && data.pages_blogs.length) {
                        data.pages_blogs.forEach(item => {
                            contentTbody.innerHTML += `
                                <tr>
                                    <td><span class="badge" style="background-color: rgba(92, 203, 179, 0.1); color: var(--mint-green); border: 1px solid rgba(92, 203, 179, 0.3); font-size: 0.75rem;">${item.type}</span></td>
                                    <td><strong>${item.title}</strong></td>
                                    <td><code style="font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 2px 4px; border-radius: 4px;">${item.keywords}</code></td>
                                    <td>${item.summary}</td>
                                </tr>
                            `;
                        });
                    } else {
                        contentTbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No content ideas generated.</td></tr>';
                    }

                    // Render 2. Backlinks Table
                    const backlinksTbody = document.getElementById('seo-table-backlinks');
                    backlinksTbody.innerHTML = '';
                    if (data.third_party_articles && data.third_party_articles.length) {
                        data.third_party_articles.forEach(item => {
                            backlinksTbody.innerHTML += `
                                <tr>
                                    <td><strong>${item.platform_type}</strong></td>
                                    <td>${item.title}</td>
                                    <td><code style="font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 2px 4px; border-radius: 4px;">${item.keywords}</code></td>
                                    <td>${item.context}</td>
                                </tr>
                            `;
                        });
                    } else {
                        backlinksTbody.innerHTML = '<tr><td colspan="4" style="text-align: center;">No off-site article suggestions.</td></tr>';
                    }

                    // Render 3. Quora Cards
                    const quoraContainer = document.getElementById('seo-cards-quora');
                    quoraContainer.innerHTML = '';
                    if (data.quora_questions && data.quora_questions.length) {
                        data.quora_questions.forEach(item => {
                            quoraContainer.innerHTML += `
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--admin-border); border-radius: var(--radius-md); padding: 1.5rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem; flex-wrap: wrap; gap: 8px;">
                                        <span class="badge" style="background-color: rgba(232, 92, 36, 0.1); color: var(--accent-orange); font-size: 0.75rem;">${item.topic}</span>
                                        <a href="https://www.quora.com/search?q=${encodeURIComponent(item.question_direction)}" target="_blank" class="btn-submit" style="width: auto; padding: 4px 10px; font-size: 0.75rem; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                                            <i data-lucide="external-link" style="width: 12px; height: 12px;"></i> Search on Quora
                                        </a>
                                    </div>
                                    <h4 style="margin: 0 0 1rem 0; font-size: 1.1rem; color: #fff;">${item.question_direction}</h4>
                                    <strong style="font-size: 0.85rem; color: var(--mint-green); display: block; margin-bottom: 0.5rem;">Tactical Answer Blueprint:</strong>
                                    <ul style="margin: 0; padding-left: 1.25rem; font-size: 0.9rem; color: rgba(255,255,255,0.85);">
                                        ${item.draft_answer_points.split('\n').map(pt => pt.trim() ? ('<li>' + pt.replace(/^[-\*\d\.]+\s*/, '') + '</li>') : '').join('')}
                                    </ul>
                                </div>
                            `;
                        });
                    } else {
                        quoraContainer.innerHTML = '<p style="text-align: center; color: var(--admin-text-muted);">No Quora suggestions generated.</p>';
                    }

                    // Render 4. Pinterest Table
                    const pinterestTbody = document.getElementById('seo-table-pinterest');
                    pinterestTbody.innerHTML = '';
                    if (data.pinterest_posts && data.pinterest_posts.length) {
                        data.pinterest_posts.forEach(item => {
                            pinterestTbody.innerHTML += `
                                <tr>
                                    <td><strong style="color: #fff;">${item.overlay_text}</strong></td>
                                    <td>${item.visual_idea}</td>
                                    <td><span class="badge" style="background: rgba(255,255,255,0.05); color: #fff; font-size: 0.75rem;">${item.category}</span></td>
                                </tr>
                            `;
                        });
                    } else {
                        pinterestTbody.innerHTML = '<tr><td colspan="3" style="text-align: center;">No Pinterest suggestions.</td></tr>';
                    }

                    // Render 5. Social Media Cards
                    const socialContainer = document.getElementById('seo-cards-social');
                    socialContainer.innerHTML = '';
                    if (data.social_media && data.social_media.length) {
                        data.social_media.forEach((item, index) => {
                            const postTextEscaped = item.post_copy.replace(/`/g, '\\`').replace(/'/g, "\\'").replace(/"/g, '&quot;');
                            socialContainer.innerHTML += `
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--admin-border); border-radius: var(--radius-md); padding: 1.25rem;">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                        <span class="badge" style="background-color: rgba(92, 102, 241, 0.1); color: #a855f7; font-size: 0.75rem;">${item.platform}</span>
                                        <button class="btn-submit" style="width: auto; padding: 4px 10px; font-size: 0.75rem;" onclick="copySeoSocialCopy(this, \`${postTextEscaped}\`)">
                                            <i data-lucide="copy" style="width: 12px; height: 12px; display: inline; vertical-align: middle;"></i> Copy Caption
                                        </button>
                                    </div>
                                    <p style="font-family: inherit; font-size: 0.9rem; white-space: pre-wrap; margin: 0 0 1rem 0; color: rgba(255,255,255,0.85);">${item.post_copy}</p>
                                    <div style="font-size: 0.8rem; background: rgba(0,0,0,0.15); padding: 8px 12px; border-radius: 4px;">
                                        <strong style="color: var(--mint-green);">Graphic Concept:</strong> ${item.graphic_concept}
                                    </div>
                                </div>
                            `;
                        });
                    } else {
                        socialContainer.innerHTML = '<p style="text-align: center; color: var(--admin-text-muted);">No social posts generated.</p>';
                    }

                    // Render 6. Bookmarking Platforms
                    const bookmarkingTbody = document.getElementById('seo-table-bookmarking');
                    bookmarkingTbody.innerHTML = '';
                    if (data.bookmarking_sites && data.bookmarking_sites.length) {
                        data.bookmarking_sites.forEach(item => {
                            const link = item.url.startsWith('http') ? item.url : 'https://' + item.url;
                            bookmarkingTbody.innerHTML += `
                                <tr>
                                    <td>
                                        <a href="${link}" target="_blank" style="color: var(--mint-green); font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            ${item.name} <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                        </a>
                                    </td>
                                    <td>${item.strategy}</td>
                                </tr>
                            `;
                        });
                    } else {
                        bookmarkingTbody.innerHTML = '<tr><td colspan="2" style="text-align: center;">No bookmarking sites suggested.</td></tr>';
                    }

                    // Render 7. Syndication Platforms
                    const syndicationTbody = document.getElementById('seo-table-syndication');
                    syndicationTbody.innerHTML = '';
                    if (data.blog_promotion && data.blog_promotion.length) {
                        data.blog_promotion.forEach(item => {
                            const link = item.url.startsWith('http') ? item.url : 'https://' + item.url;
                            syndicationTbody.innerHTML += `
                                <tr>
                                    <td>
                                        <a href="${link}" target="_blank" style="color: var(--mint-green); font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                            ${item.name} <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                        </a>
                                    </td>
                                    <td>${item.strategy}</td>
                                </tr>
                            `;
                        });
                    } else {
                        syndicationTbody.innerHTML = '<tr><td colspan="2" style="text-align: center;">No syndication sites suggested.</td></tr>';
                    }

                    // Render 8. Other growth tactics
                    const otherContainer = document.getElementById('seo-cards-other');
                    otherContainer.innerHTML = '';
                    if (data.other_backlinks && data.other_backlinks.length) {
                        data.other_backlinks.forEach(item => {
                            otherContainer.innerHTML += `
                                <div style="background: rgba(255,255,255,0.02); border: 1px solid var(--admin-border); border-radius: var(--radius-md); padding: 1.25rem;">
                                    <h4 style="margin: 0 0 0.5rem 0; font-size: 1.05rem; color: var(--mint-green); font-weight: 600;">${item.strategy_name}</h4>
                                    <p style="font-size: 0.88rem; margin: 0; color: rgba(255,255,255,0.85);">${item.action_steps}</p>
                                </div>
                            `;
                        });
                    } else {
                        otherContainer.innerHTML = '<p style="grid-column: 1 / -1; text-align: center; color: var(--admin-text-muted);">No additional tactics generated.</p>';
                    }

                    // Trigger Lucide icons instantiation
                    setTimeout(() => lucide.createIcons(), 100);

                    // Show results and switch to Content Tab by default
                    results.style.display = 'block';
                    switchSeoResultTab('content');
                    results.scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('Error: ' + (res.error || 'Failed to generate SEO strategy.'));
                }
            })
            .catch(err => {
                loader.style.display = 'none';
                alert('Network Error: ' + err.message);
            });
        }

        // Generate and download a beautifully-styled PDF report on the client side
        function downloadSeoPdf() {
            if (!latestSeoData) {
                alert('No SEO data available to download. Please generate recommendations first.');
                return;
            }
            
            const keywords = document.getElementById('seo_keywords').value.trim();
            const dateStr = new Date().toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
            
            // Build temporary off-screen container for rendering
            const printEl = document.createElement('div');
            printEl.style.padding = '20px';
            printEl.style.color = '#333333';
            printEl.style.backgroundColor = '#ffffff';
            printEl.style.fontFamily = "'Outfit', 'Inter', sans-serif";
            printEl.style.lineHeight = '1.5';
            
            let html = `
                <div style="border-bottom: 2px solid #F5B400; padding-bottom: 15px; margin-bottom: 25px;">
                    <h1 style="color: #143572; margin: 0 0 5px 0; font-size: 26px;">Tenderfold - AI SEO Recommendation Blueprint</h1>
                    <p style="color: #555; margin: 0; font-size: 13px;"><strong>Generated Date:</strong> ${dateStr} | <strong>Target Keywords:</strong> ${keywords}</p>
                </div>
                
                <h2 style="color: #0a1112; font-size: 18px; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">1. On-Site Content structure Recommendations</h2>
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; margin-bottom: 30px;">
                    <thead>
                        <tr style="background-color: #f7f9f9; border-bottom: 2px solid #ddd;">
                            <th style="padding: 10px; text-align: left; width: 15%; border: 1px solid #ddd; font-weight: bold;">Type</th>
                            <th style="padding: 10px; text-align: left; width: 30%; border: 1px solid #ddd; font-weight: bold;">Recommended Title</th>
                            <th style="padding: 10px; text-align: left; width: 20%; border: 1px solid #ddd; font-weight: bold;">Keywords</th>
                            <th style="padding: 10px; text-align: left; width: 35%; border: 1px solid #ddd; font-weight: bold;">Outline & Search Intent</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            if (latestSeoData.pages_blogs && latestSeoData.pages_blogs.length) {
                latestSeoData.pages_blogs.forEach(item => {
                    html += `
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px 10px; border: 1px solid #ddd; font-weight: bold; color: #0f766e;">${item.type}</td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd;"><strong>${item.title}</strong></td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd; font-family: monospace; background-color: #f8fafc;">${item.keywords}</td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd; color: #444;">${item.summary}</td>
                        </tr>
                    `;
                });
            } else {
                html += `<tr><td colspan="4" style="text-align: center; padding: 12px;">No content ideas.</td></tr>`;
            }
            
            html += `
                    </tbody>
                </table>
                
                <div style="page-break-after: always;"></div>
                
                <h2 style="color: #0a1112; font-size: 18px; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">2. Third-Party Off-Site Articles & Backlinks</h2>
                <table style="width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 12px; margin-bottom: 30px;">
                    <thead>
                        <tr style="background-color: #f7f9f9; border-bottom: 2px solid #ddd;">
                            <th style="padding: 10px; text-align: left; width: 20%; border: 1px solid #ddd; font-weight: bold;">Platform Type</th>
                            <th style="padding: 10px; text-align: left; width: 30%; border: 1px solid #ddd; font-weight: bold;">Article Title Idea</th>
                            <th style="padding: 10px; text-align: left; width: 20%; border: 1px solid #ddd; font-weight: bold;">Anchor Keywords</th>
                            <th style="padding: 10px; text-align: left; width: 30%; border: 1px solid #ddd; font-weight: bold;">Backlink Integration Context</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            if (latestSeoData.third_party_articles && latestSeoData.third_party_articles.length) {
                latestSeoData.third_party_articles.forEach(item => {
                    html += `
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px 10px; border: 1px solid #ddd; font-weight: bold;">${item.platform_type}</td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd;"><strong>${item.title}</strong></td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd; font-family: monospace; background-color: #f8fafc;">${item.keywords}</td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd; color: #444;">${item.context}</td>
                        </tr>
                    `;
                });
            } else {
                html += `<tr><td colspan="4" style="text-align: center; padding: 12px;">No guest posting suggestions.</td></tr>`;
            }
            
            html += `
                    </tbody>
                </table>
                
                <h2 style="color: #0a1112; font-size: 18px; margin-top: 30px; border-bottom: 1px solid #ddd; padding-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">3. Quora Q&A Authority Roadmap</h2>
                <div style="display: flex; flex-direction: column; gap: 15px; margin-top: 10px; margin-bottom: 30px;">
            `;
            
            if (latestSeoData.quora_questions && latestSeoData.quora_questions.length) {
                latestSeoData.quora_questions.forEach(item => {
                    html += `
                        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 6px; background-color: #fafafa;">
                            <span style="font-size: 10px; font-weight: bold; background: #e85c24; color: white; padding: 2px 8px; border-radius: 4px; display: inline-block; margin-bottom: 8px; text-transform: uppercase;">${item.topic}</span>
                            <h4 style="margin: 0 0 10px 0; font-size: 14px; color: #0a1112; font-weight: bold;">Question: ${item.question_direction}</h4>
                            <p style="margin: 0 0 5px 0; font-size: 12px; color: #555; font-weight: bold;">Tactical Answer Outline:</p>
                            <ul style="margin: 0; padding-left: 20px; font-size: 12px; color: #444;">
                                ${item.draft_answer_points.split('\n').map(pt => pt.trim() ? ('<li>' + pt.replace(/^[-\*\d\.]+\s*/, '') + '</li>') : '').join('')}
                            </ul>
                        </div>
                    `;
                });
            } else {
                html += `<p style="font-size: 13px;">No Quora suggestions.</p>`;
            }
            
            html += `
                </div>
                
                <div style="page-break-after: always;"></div>
                
                <h2 style="color: #0a1112; font-size: 18px; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">4. Pinterest Graphics & Written Social Copy</h2>
                <h3 style="font-size: 14px; margin-top: 15px; margin-bottom: 8px; color: #333; font-weight: bold;">Pinterest Overlay Graphics</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 35px;">
                    <thead>
                        <tr style="background-color: #f7f9f9; border-bottom: 2px solid #ddd;">
                            <th style="padding: 10px; text-align: left; width: 30%; border: 1px solid #ddd; font-weight: bold;">Overlay Bold Text</th>
                            <th style="padding: 10px; text-align: left; width: 50%; border: 1px solid #ddd; font-weight: bold;">Visual Graphic Concept</th>
                            <th style="padding: 10px; text-align: left; width: 20%; border: 1px solid #ddd; font-weight: bold;">Category</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            if (latestSeoData.pinterest_posts && latestSeoData.pinterest_posts.length) {
                latestSeoData.pinterest_posts.forEach(item => {
                    html += `
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px 10px; border: 1px solid #ddd; font-weight: bold;">${item.overlay_text}</td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd; color: #444;">${item.visual_idea}</td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd;">${item.category}</td>
                        </tr>
                    `;
                });
            } else {
                html += `<tr><td colspan="3" style="text-align: center; padding: 12px;">No Pinterest suggestions.</td></tr>`;
            }
            
            html += `
                    </tbody>
                </table>
                
                <h3 style="font-size: 14px; margin-top: 20px; margin-bottom: 10px; color: #333; font-weight: bold;">Social Media Written Copy</h3>
                <div style="display: flex; flex-direction: column; gap: 15px;">
            `;
            
            if (latestSeoData.social_media && latestSeoData.social_media.length) {
                latestSeoData.social_media.forEach(item => {
                    html += `
                        <div style="border: 1px solid #ddd; padding: 15px; border-radius: 6px; background-color: #fcfcfc;">
                            <strong style="color: #4f46e5; font-size: 12px; display: block; margin-bottom: 6px; text-transform: uppercase;">Platform: ${item.platform}</strong>
                            <p style="margin: 0 0 12px 0; font-size: 12px; white-space: pre-wrap; color: #333; line-height: 1.6; background-color: #fff; padding: 10px; border: 1px solid #eee; border-radius: 4px;">${item.post_copy}</p>
                            <div style="font-size: 11px; color: #444; background: #f1f5f9; padding: 8px 12px; border-radius: 4px; border-left: 3px solid #64748b;">
                                <strong>Graphic Concept:</strong> ${item.graphic_concept}
                            </div>
                        </div>
                    `;
                });
            } else {
                html += `<p style="font-size: 13px;">No social copy suggestions.</p>`;
            }
            
            html += `
                </div>
                
                <div style="page-break-after: always;"></div>
                
                <h2 style="color: #0a1112; font-size: 18px; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">5. Social Bookmarking & Syndication Directories</h2>
                <h3 style="font-size: 14px; margin-top: 15px; margin-bottom: 8px; color: #333; font-weight: bold;">Social Bookmarking Websites</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 30px;">
                    <thead>
                        <tr style="background-color: #f7f9f9; border-bottom: 2px solid #ddd;">
                            <th style="padding: 10px; text-align: left; width: 30%; border: 1px solid #ddd; font-weight: bold;">Platform</th>
                            <th style="padding: 10px; text-align: left; width: 70%; border: 1px solid #ddd; font-weight: bold;">Tactical Submission Strategy</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            if (latestSeoData.bookmarking_sites && latestSeoData.bookmarking_sites.length) {
                latestSeoData.bookmarking_sites.forEach(item => {
                    html += `
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px 10px; border: 1px solid #ddd;"><strong>${item.name}</strong><br><span style="font-size: 10px; color: #666;">${item.url}</span></td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd; color: #444;">${item.strategy}</td>
                        </tr>
                    `;
                });
            } else {
                html += `<tr><td colspan="2" style="text-align: center; padding: 12px;">No bookmarking sites.</td></tr>`;
            }
            
            html += `
                    </tbody>
                </table>
                
                <h3 style="font-size: 14px; margin-top: 20px; margin-bottom: 8px; color: #333; font-weight: bold;">Blog Syndication Platforms</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 12px; margin-bottom: 30px;">
                    <thead>
                        <tr style="background-color: #f7f9f9; border-bottom: 2px solid #ddd;">
                            <th style="padding: 10px; text-align: left; width: 30%; border: 1px solid #ddd; font-weight: bold;">Platform</th>
                            <th style="padding: 10px; text-align: left; width: 70%; border: 1px solid #ddd; font-weight: bold;">Syndication Strategy</th>
                        </tr>
                    </thead>
                    <tbody>
            `;
            
            if (latestSeoData.blog_promotion && latestSeoData.blog_promotion.length) {
                latestSeoData.blog_promotion.forEach(item => {
                    html += `
                        <tr style="border-bottom: 1px solid #ddd;">
                            <td style="padding: 8px 10px; border: 1px solid #ddd;"><strong>${item.name}</strong><br><span style="font-size: 10px; color: #666;">${item.url}</span></td>
                            <td style="padding: 8px 10px; border: 1px solid #ddd; color: #444;">${item.strategy}</td>
                        </tr>
                    `;
                });
            } else {
                html += `<tr><td colspan="2" style="text-align: center; padding: 12px;">No syndication sites.</td></tr>`;
            }
            
            html += `
                    </tbody>
                </table>
                
                <h2 style="color: #0a1112; font-size: 18px; margin-top: 20px; border-bottom: 1px solid #ddd; padding-bottom: 5px; text-transform: uppercase; letter-spacing: 0.5px;">6. Advanced Growth Tactics</h2>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px;">
            `;
            
            if (latestSeoData.other_backlinks && latestSeoData.other_backlinks.length) {
                latestSeoData.other_backlinks.forEach(item => {
                    html += `
                        <div style="border: 1px solid #ddd; padding: 12px; border-radius: 6px; background-color: #fafafa;">
                            <h4 style="margin: 0 0 6px 0; font-size: 13px; color: #0f766e; font-weight: bold;">${item.strategy_name}</h4>
                            <p style="margin: 0; font-size: 11px; color: #555; line-height: 1.5;">${item.action_steps}</p>
                        </div>
                    `;
                });
            } else {
                html += `<p style="font-size: 13px;">No additional tactics.</p>`;
            }
            
            html += `
                </div>
            `;
            
            printEl.innerHTML = html;
            
            const opt = {
                margin:       [15, 15, 15, 15],
                filename:     'Tenderfold_SEO_Blueprint_' + keywords.replace(/[^a-z0-9]+/gi, '_') + '.pdf',
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true, letterRendering: true },
                jsPDF:        { unit: 'mm', format: 'a4', orientation: 'portrait' },
                pagebreak:    { mode: ['avoid-all', 'css', 'legacy'] }
            };
            
            html2pdf().set(opt).from(printEl).save();
        }

        // GMB Modal Management
        function openGmbShareModal(type, data) {
            document.getElementById('gmb_post_type').value = type;
            document.getElementById('gmb_post_id').value = data.id;
            
            const baseUrl = 'https://tenderfold.in';
            let targetUrl = '';
            if (type === 'blog') {
                targetUrl = baseUrl + '/blog/' + data.slug;
            } else if (type === 'service') {
                targetUrl = baseUrl + '/services/' + data.slug;
            } else {
                targetUrl = baseUrl + '/l/' + data.slug;
            }
            
            document.getElementById('gmb_target_url').value = targetUrl;
            document.getElementById('gmb_post_cta_url').value = targetUrl;
            document.getElementById('gmb_post_text').value = '';
            
            // Open modal
            document.getElementById('gmb-share-modal').classList.add('show');
            
            // Auto draft with AI
            generateGmbDraft();
        }

        function closeGmbModal() {
            document.getElementById('gmb-share-modal').classList.remove('show');
        }

        function generateGmbDraft() {
            const type = document.getElementById('gmb_post_type').value;
            const id = document.getElementById('gmb_post_id').value;
            const form = document.getElementById('gmb-form-wrapper');
            const loader = document.getElementById('gmb-loader');
            
            form.style.display = 'none';
            loader.style.display = 'block';
            
            fetch("{{ route('admin.ai.gmb-draft') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ type: type, id: id })
            })
            .then(res => res.json())
            .then(res => {
                loader.style.display = 'none';
                form.style.display = 'block';
                if (res.success && res.draft) {
                    document.getElementById('gmb_post_text').value = res.draft;
                } else {
                    alert('AI Drafting failed: ' + (res.error || 'Unknown error'));
                    document.getElementById('gmb_post_text').value = 'Failed to draft post. Please write manually.';
                }
            })
            .catch(err => {
                loader.style.display = 'none';
                form.style.display = 'block';
                alert('Network Error drafting post: ' + err.message);
            });
        }

        function copyAndOpenGmb() {
            const text = document.getElementById('gmb_post_text').value;
            if (!text) {
                alert('No text draft to copy!');
                return;
            }
            navigator.clipboard.writeText(text).then(() => {
                showToast('Post content copied to clipboard!', 'success');
                setTimeout(() => {
                    window.open('https://business.google.com/', '_blank');
                }, 800);
            }).catch(err => {
                alert('Failed to copy to clipboard.');
            });
        }

        function publishGmbPostDirect() {
            const text = document.getElementById('gmb_post_text').value;
            const url = document.getElementById('gmb_target_url').value;
            const publishBtn = document.getElementById('gmb-publish-btn');
            
            if (!text) {
                alert('Please enter post text.');
                return;
            }
            
            const originalHtml = publishBtn.innerHTML;
            publishBtn.disabled = true;
            publishBtn.innerHTML = 'Publishing...';
            
            fetch("{{ route('admin.gmb.publish') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ text: text, url: url })
            })
            .then(res => res.json())
            .then(res => {
                publishBtn.disabled = false;
                publishBtn.innerHTML = originalHtml;
                if (res.success) {
                    showToast('Successfully published post directly to Google Business Profile!', 'success');
                    closeGmbModal();
                } else {
                    alert('API Publish failed: ' + (res.error || 'Unknown error'));
                }
            })
            .catch(err => {
                publishBtn.disabled = false;
                publishBtn.innerHTML = originalHtml;
                alert('Network Error publishing: ' + err.message);
            });
        }

        // Toggle Lead Details
        function toggleLeadDetails(id) {
            const row = document.getElementById('lead-details-' + id);
            if (!row) return;
            const currentDisp = row.style.display || getComputedStyle(row).display;
            if (currentDisp === 'none') {
                row.style.display = 'table-row';
                setTimeout(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); }, 10);
            } else {
                row.style.display = 'none';
            }
        }

        // Bulk Lead Operations
        function toggleAllLeads(masterCheckbox) {
            const checkboxes = document.querySelectorAll('.lead-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = masterCheckbox.checked;
            });
            updateBulkDeleteState();
        }

        function updateBulkDeleteState() {
            const checkboxes = document.querySelectorAll('.lead-checkbox');
            const checkedBoxes = Array.from(checkboxes).filter(cb => cb.checked);
            const bulkDeleteBtn = document.getElementById('btn-bulk-delete');
            const countSpan = document.getElementById('bulk-select-count');
            
            if (checkedBoxes.length > 0) {
                bulkDeleteBtn.style.display = 'inline-flex';
                countSpan.textContent = checkedBoxes.length;
            } else {
                bulkDeleteBtn.style.display = 'none';
                countSpan.textContent = '0';
            }
        }

        function submitBulkDelete() {
            if (confirm('Are you sure you want to permanently delete all selected leads?')) {
                document.getElementById('bulk-delete-form').submit();
            }
        }

        // Admin Live Chat Variables (declared at the top of script)

        // Chat pollings are managed globally inside the unified DOMContentLoaded listener

        function escapeHTML(str) {
            if (!str) return '';
            return str.replace(/[&<>'"]/g, 
                tag => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#39;', '"': '&quot;' }[tag] || tag)
            );
        }

        let previousUnreadSum = null;

        function playNotificationChime() {
            try {
                const AudioContext = window.AudioContext || window.webkitAudioContext;
                if (!AudioContext) return;
                const context = new AudioContext();
                
                const playNote = (freq, start, duration) => {
                    const osc = context.createOscillator();
                    const gain = context.createGain();
                    
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(freq, start);
                    
                    gain.gain.setValueAtTime(0.08, start);
                    gain.gain.exponentialRampToValueAtTime(0.001, start + duration);
                    
                    osc.connect(gain);
                    gain.connect(context.destination);
                    
                    osc.start(start);
                    osc.stop(start + duration);
                };
                
                const now = context.currentTime;
                playNote(523.25, now, 0.25); // C5
                playNote(659.25, now + 0.15, 0.4); // E5
            } catch (e) {
                console.error('Audio synthesizer chime failed:', e);
            }
        }

        function loadAdminChatSessions() {
            fetch('/admin/chats')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderAdminChatSessions(data.sessions);
                    }
                })
                .catch(err => console.error('Error fetching admin chat sessions:', err));
        }

        function renderAdminChatSessions(sessions) {
            const list = document.getElementById('admin-chat-sessions-list');
            
            // Build signature to check for content and selection state changes
            const sessionsSignature = JSON.stringify(sessions.map(s => ({
                id: s.session_id,
                msg: s.message,
                unread: s.unread_count,
                selected: s.session_id === currentAdminSessionId,
                time: s.created_at
            })));
            
            // If nothing changed, return immediately to prevent sidebar DOM recreation & flickering
            if (sessionsSignature === lastSessionsJson) {
                return;
            }
            lastSessionsJson = sessionsSignature;
            
            // Update sidebar unread totals for menu badges
            let totalUnread = 0;
            sessions.forEach(s => totalUnread += s.unread_count);
            
            if (previousUnreadSum !== null && totalUnread > previousUnreadSum) {
                playNotificationChime();
                if (typeof showToast === 'function') {
                    showToast('New Live Chat message received from visitor!', 'success');
                }
                if ("Notification" in window) {
                    if (Notification.permission === "granted") {
                        new Notification("New Live Chat Message", {
                            body: "A website visitor sent a new chat message to Tenderfold.",
                            icon: "/favicon.ico"
                        });
                    } else if (Notification.permission === "default") {
                        Notification.requestPermission();
                    }
                }
            }
            previousUnreadSum = totalUnread;
            
            const totalBadge = document.getElementById('admin-chat-total-badge');
            const mobileBadge = document.getElementById('admin-chat-mobile-badge');
            
            if (totalUnread > 0) {
                totalBadge.textContent = totalUnread;
                totalBadge.style.display = 'inline-flex';
                mobileBadge.textContent = totalUnread;
                mobileBadge.style.display = 'inline-flex';
            } else {
                totalBadge.style.display = 'none';
                mobileBadge.style.display = 'none';
            }

            if (sessions.length === 0) {
                list.innerHTML = `<div style="padding: 3rem 1.5rem; text-align: center; color: var(--admin-text-muted); font-size: 0.85rem;">No active chat conversations.</div>`;
                return;
            }

            list.innerHTML = '';
            sessions.forEach(s => {
                const isSelected = s.session_id === currentAdminSessionId;
                const time = new Date(s.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
                
                const itemDiv = document.createElement('div');
                itemDiv.onclick = () => selectAdminChat(s.session_id);
                itemDiv.style.padding = '1rem 1.25rem';
                itemDiv.style.borderBottom = '1px solid var(--admin-border)';
                itemDiv.style.cursor = 'pointer';
                itemDiv.style.backgroundColor = isSelected ? 'rgba(92, 203, 179, 0.08)' : 'transparent';
                itemDiv.style.borderLeft = isSelected ? '4px solid var(--primary-teal)' : '4px solid transparent';
                itemDiv.style.transition = 'all 0.2s';
                
                itemDiv.innerHTML = `
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                        <strong style="color: ${isSelected ? 'var(--mint-green)' : '#ffffff'}; font-size: 0.9rem; font-family: 'Outfit';">Session ${String(s.session_id || '').slice(-6)}</strong>
                        <span style="font-size: 0.7rem; color: var(--admin-text-muted);">${time}</span>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 0.8rem; color: #94a3b8; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;">
                            ${s.sender === 'admin' ? 'You: ' : ''}${escapeHTML(s.message)}
                        </span>
                        ${s.unread_count > 0 ? `<span style="background-color: #ef4444; color: white; font-size: 0.65rem; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">${s.unread_count}</span>` : ''}
                    </div>
                `;
                
                itemDiv.onmouseover = () => { if (!isSelected) itemDiv.style.backgroundColor = 'rgba(255,255,255,0.02)'; };
                itemDiv.onmouseout = () => { if (!isSelected) itemDiv.style.backgroundColor = 'transparent'; };
                
                list.appendChild(itemDiv);
            });
        }

        function selectAdminChat(sessionId) {
            currentAdminSessionId = sessionId;
            lastMessagesJson = ''; // Clear message signature to force reload of messages panel
            loadAdminChatSessions();
            
            document.getElementById('admin-chat-input-wrap').style.display = 'flex';
            document.getElementById('admin-chat-delete-btn').style.display = 'inline-flex';
            document.getElementById('admin-chat-refresh-btn').style.display = 'inline-flex';
            
            document.getElementById('admin-chat-header-title').textContent = `Session ${String(sessionId || '').slice(-6)}`;
            document.getElementById('admin-chat-header-subtitle').textContent = `Full Session ID: ${sessionId}`;
            
            loadAdminChatMessages();
            
            clearInterval(adminMessagesPoll);
            adminMessagesPoll = setInterval(loadAdminChatMessages, 60000);
        }



        function loadAdminChatMessages() {
            if (!currentAdminSessionId) return;
            
            fetch(`/admin/chats/${currentAdminSessionId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderAdminChatMessages(data.messages);
                    }
                })
                .catch(err => console.error('Error fetching admin session messages:', err));
        }

        function renderAdminChatMessages(messages) {
            const wrap = document.getElementById('admin-chat-messages-wrap');
            
            const messagesSignature = JSON.stringify(messages.map(m => ({
                id: m.id,
                sender: m.sender,
                msg: m.message,
                time: m.created_at
            })));
            
            if (messagesSignature === lastMessagesJson) {
                return;
            }
            lastMessagesJson = messagesSignature;
            
            wrap.innerHTML = '';
            
            messages.forEach(msg => {
                const isMsgAdmin = msg.sender === 'admin';
                const msgDiv = document.createElement('div');
                msgDiv.style.display = 'flex';
                msgDiv.style.justifyContent = isMsgAdmin ? 'flex-end' : 'flex-start';
                
                const time = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                msgDiv.innerHTML = `
                    <div style="max-width: 70%; background-color: ${isMsgAdmin ? 'var(--primary-teal)' : 'var(--admin-dark-sidebar)'}; color: white; padding: 0.65rem 1rem; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); font-size: 0.9rem; line-height: 1.4; margin-bottom: 4px;">
                        <div style="word-break: break-word;">${escapeHTML(msg.message)}</div>
                        <div style="font-size: 0.65rem; text-align: right; margin-top: 4px; color: ${isMsgAdmin ? 'rgba(255,255,255,0.7)' : 'var(--admin-text-muted)'};">${time}</div>
                    </div>
                `;
                wrap.appendChild(msgDiv);
            });
            
            wrap.scrollTop = wrap.scrollHeight;
        }


        function handleAdminChatKey(e) {
            if (e.key === 'Enter') {
                sendAdminReply();
            }
        }

        function sendAdminReply() {
            if (!currentAdminSessionId) return;
            
            const input = document.getElementById('admin-chat-reply-input');
            const msg = input.value.trim();
            if (!msg) return;
            
            input.value = '';
            
            fetch(`/admin/chats/${currentAdminSessionId}/reply`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: msg })
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    lastMessagesJson = '';
                    loadAdminChatMessages();
                    loadAdminChatSessions();
                }

            })
            .catch(err => console.error('Error sending admin reply:', err));
        }

        function deleteCurrentChat() {
            if (!currentAdminSessionId) return;
            
            if (confirm('Are you sure you want to permanently delete this chat session and all its messages?')) {
                fetch(`/admin/chats/${currentAdminSessionId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        currentAdminSessionId = null;
                        lastSessionsJson = '';
                        lastMessagesJson = '';
                        clearInterval(adminMessagesPoll);

                        
                        document.getElementById('admin-chat-messages-wrap').innerHTML = `
                            <div style="margin: auto; text-align: center; color: var(--admin-text-muted);">
                                <i data-lucide="message-square" style="width: 48px; height: 48px; stroke-width: 1.25; margin-bottom: 0.75rem; color: var(--admin-border);"></i>
                                <p style="font-size: 0.95rem; margin: 0;">No Chat Selected</p>
                            </div>
                        `;
                        document.getElementById('admin-chat-header-title').textContent = 'Select a Chat';
                        document.getElementById('admin-chat-header-subtitle').textContent = 'Please pick a patient chat session from the list to start responding.';
                        document.getElementById('admin-chat-delete-btn').style.display = 'none';
                        document.getElementById('admin-chat-refresh-btn').style.display = 'none';
                        document.getElementById('admin-chat-input-wrap').style.display = 'none';

                        
                        setTimeout(() => lucide.createIcons(), 10);
                        loadAdminChatSessions();
                    }
                })
                .catch(err => console.error('Error deleting chat:', err));
            }
        }

        // Initialize background silent badge checkers
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                if (localStorage.getItem('active_tab') === 'livechat' && "{{ $activeTab }}" !== 'livechat') {
                    switchTab('livechat');
                } else {
                    fetch('/admin/chats')
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                let totalUnread = 0;
                                data.sessions.forEach(s => totalUnread += s.unread_count);
                                const totalBadge = document.getElementById('admin-chat-total-badge');
                                const mobileBadge = document.getElementById('admin-chat-mobile-badge');
                                if (totalUnread > 0) {
                                    totalBadge.textContent = totalUnread;
                                    totalBadge.style.display = 'inline-flex';
                                    mobileBadge.textContent = totalUnread;
                                    mobileBadge.style.display = 'inline-flex';
                                }
                            }
                        });
                }
            }, 2000);
        });

        // Initialize CKEditor defensively
        // Initialize CKEditor defensively
        let blogEditor;
        if (typeof ClassicEditor !== 'undefined' && document.querySelector('#blog_content')) {
            ClassicEditor
                .create(document.querySelector('#blog_content'))
                .then(editor => {
                    blogEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        let serviceIntroEditor;
        let serviceTipsEditor;
        if (typeof ClassicEditor !== 'undefined' && document.querySelector('#ser_intro_content')) {
            ClassicEditor
                .create(document.querySelector('#ser_intro_content'))
                .then(editor => {
                    serviceIntroEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        if (typeof ClassicEditor !== 'undefined' && document.querySelector('#ser_tips_content')) {
            ClassicEditor
                .create(document.querySelector('#ser_tips_content'))
                .then(editor => {
                    serviceTipsEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        let treatmentEditor;
        if (typeof ClassicEditor !== 'undefined' && document.querySelector('#treat_description')) {
            ClassicEditor
                .create(document.querySelector('#treat_description'))
                .then(editor => {
                    treatmentEditor = editor;
                    window.treatmentEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        let specialistEditor;
        if (typeof ClassicEditor !== 'undefined' && document.querySelector('#spec_description')) {
            ClassicEditor
                .create(document.querySelector('#spec_description'))
                .then(editor => {
                    specialistEditor = editor;
                    window.specialistEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        let cmsEditor;
        if (typeof ClassicEditor !== 'undefined' && document.querySelector('#cms_description')) {
            ClassicEditor
                .create(document.querySelector('#cms_description'))
                .then(editor => {
                    cmsEditor = editor;
                    window.cmsEditor = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        function escapeJsString(str) {
            if (!str) return '';
            return str.replace(/'/g, "\\'").replace(/"/g, '\\"');
        }

        // Blog Edit functions via dynamic AJAX
        function editBlog(id) {
            document.getElementById('blog-form-title-text').innerText = 'Loading Blog details...';
            
            fetch('/admin/blogs/' + id + '/detail')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('blog-form-title-text').innerText = 'Edit Blog Article: ' + data.title;
                    document.getElementById('blog-ai-btn').style.display = 'none';
                    document.getElementById('blog-form').action = '/admin/blogs/' + data.id + '/update';
                    document.getElementById('blog-form-method').value = 'POST';
                    document.getElementById('blog_title').value = data.title;
                    document.getElementById('blog_slug').value = data.slug;
                    document.getElementById('blog_category').value = data.category;
                    document.getElementById('blog_published_at').value = data.published_at;
                    document.getElementById('blog_summary').value = data.summary;
                    document.getElementById('blog_schema_markup').value = data.schema_markup || '';
                    document.getElementById('blog_metatitle').value = data.metatitle || '';
                    document.getElementById('blog_metadescription').value = data.metadescription || '';
                    document.getElementById('blog_metakeywords').value = data.metakeywords || '';
                    if (blogEditor) {
                        blogEditor.setData(data.content || '');
                    } else {
                        document.getElementById('blog_content').value = data.content || '';
                    }
                    document.getElementById('blog-submit-btn').innerText = 'Update Blog Article';
                    document.getElementById('blog_image_url').value = (data.image_path && (data.image_path.startsWith('http') || data.image_path.startsWith('//'))) ? data.image_path : '';
                    document.getElementById('blog-cancel-btn').style.display = 'inline-block';
                    
                    document.getElementById('blog-form-container').scrollIntoView({ behavior: 'smooth' });
                })
                .catch(err => {
                    console.error('Error fetching blog details:', err);
                    alert('Failed to load blog details. Please try again.');
                    cancelBlogEdit();
                });
        }



        function cancelBlogEdit() {
            document.getElementById('blog-form-title-text').innerText = 'Create New Blog Article';
            document.getElementById('blog-ai-btn').style.display = 'inline-flex';
            document.getElementById('blog-form').action = "{{ route('admin.blogs.store') }}";
            document.getElementById('blog-form-method').value = 'POST';
            document.getElementById('blog_title').value = '';
            document.getElementById('blog_slug').value = '';
            document.getElementById('blog_category').selectedIndex = 0;
            document.getElementById('blog_published_at').value = "{{ date('Y-m-d\TH:i') }}";
            document.getElementById('blog_summary').value = '';
            document.getElementById('blog_schema_markup').value = '';
            document.getElementById('blog_metatitle').value = '';
            document.getElementById('blog_metadescription').value = '';
            document.getElementById('blog_metakeywords').value = '';
            document.getElementById('blog_image_url').value = '';
            if (blogEditor) {
                blogEditor.setData('');
            } else {
                document.getElementById('blog_content').value = '';
            }
            document.getElementById('blog-submit-btn').innerText = 'Publish Blog Article';
            document.getElementById('blog-cancel-btn').style.display = 'none';
        }


        // HTML escaping helper
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        // FAQ Repeater actions
        function addFaqInput(question = '', answer = '') {
            const container = document.getElementById('faq-repeater-container');
            const faqRow = document.createElement('div');
            faqRow.className = 'faq-repeater-row';
            faqRow.style = 'background: rgba(255,255,255,0.01); border: 1px solid var(--admin-border); padding: 1rem; border-radius: var(--radius-md); position: relative; margin-bottom: 0.5rem;';
            faqRow.innerHTML = `
                <button type="button" onclick="this.parentElement.remove()" style="position: absolute; right: 10px; top: 10px; background: none; border: none; color: #ef4444; cursor: pointer; font-size: 1.2rem;" title="Remove FAQ">&times;</button>
                <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-top: 0.5rem;">
                    <div>
                        <label style="font-size: 0.8rem; color: rgba(255,255,255,0.6); display:block; margin-bottom:0.25rem;">Question</label>
                        <input type="text" name="faq_questions[]" class="form-control" value="${escapeHtml(question)}" required placeholder="e.g. What is the minimum interest rate?">
                    </div>
                    <div>
                        <label style="font-size: 0.8rem; color: rgba(255,255,255,0.6); display:block; margin-bottom:0.25rem;">Answer</label>
                        <textarea name="faq_answers[]" class="form-control" rows="2" required placeholder="e.g. The minimum interest rate starts from 10.49% p.a.">${escapeHtml(answer)}</textarea>
                    </div>
                </div>
            `;
            container.appendChild(faqRow);
            lucide.createIcons();
        }

        // Slide Edit functions
        function editSlide(data) {
            const form = document.getElementById('slide-form');
            const title = document.getElementById('slide-form-title');
            const submitBtn = document.getElementById('btn-slide-submit');
            const cancelBtn = document.getElementById('btn-slide-cancel');

            if (!form) return;

            form.action = '/admin/slides/' + data.id + '/update';
            document.getElementById('slide_id').value = data.id;
            document.getElementById('slide_eyebrow').value = data.eyebrow || '';
            document.getElementById('slide_title').value = data.title || '';
            document.getElementById('slide_subtitle').value = data.subtitle || '';
            document.getElementById('slide_btn_text').value = data.button_text || '';
            document.getElementById('slide_btn_link').value = data.button_link || '';
            document.getElementById('slide_btn_2_text').value = data.button_2_text || '';
            document.getElementById('slide_btn_2_link').value = data.button_2_link || '';
            document.getElementById('slide_trust_1_num').value = data.trust_1_num || '';
            document.getElementById('slide_trust_1_label').value = data.trust_1_label || '';
            document.getElementById('slide_trust_2_num').value = data.trust_2_num || '';
            document.getElementById('slide_trust_2_label').value = data.trust_2_label || '';
            document.getElementById('slide_trust_3_num').value = data.trust_3_num || '';
            document.getElementById('slide_trust_3_label').value = data.trust_3_label || '';
            document.getElementById('slide_sort').value = data.sort_order || 0;
            document.getElementById('slide_image_url').value = (data.image_path && data.image_path.startsWith('http')) ? data.image_path : '';
            document.getElementById('slide_mobile_image_url').value = (data.mobile_image_path && data.mobile_image_path.startsWith('http')) ? data.mobile_image_path : '';

            if (title) title.innerText = 'Edit Hero Slide: ' + (data.title || '');
            if (submitBtn) submitBtn.innerText = '💾 Save Slide Changes';
            if (cancelBtn) cancelBtn.style.display = 'inline-block';

            const card = document.getElementById('slide-form-card');
            if (card) card.scrollIntoView({ behavior: 'smooth' });
        }

        function resetSlideForm() {
            const form = document.getElementById('slide-form');
            const title = document.getElementById('slide-form-title');
            const submitBtn = document.getElementById('btn-slide-submit');
            const cancelBtn = document.getElementById('btn-slide-cancel');

            if (!form) return;

            form.action = "{{ route('admin.slides.store') }}";
            document.getElementById('slide_id').value = '';
            document.getElementById('slide_eyebrow').value = '';
            document.getElementById('slide_title').value = '';
            document.getElementById('slide_subtitle').value = '';
            document.getElementById('slide_btn_text').value = '';
            document.getElementById('slide_btn_link').value = '';
            document.getElementById('slide_btn_2_text').value = '';
            document.getElementById('slide_btn_2_link').value = '';
            document.getElementById('slide_trust_1_num').value = '';
            document.getElementById('slide_trust_1_label').value = '';
            document.getElementById('slide_trust_2_num').value = '';
            document.getElementById('slide_trust_2_label').value = '';
            document.getElementById('slide_trust_3_num').value = '';
            document.getElementById('slide_trust_3_label').value = '';
            document.getElementById('slide_sort').value = '0';
            document.getElementById('slide_image_url').value = '';
            document.getElementById('slide_mobile_image_url').value = '';
            document.getElementById('slide_image').value = '';
            document.getElementById('slide_mobile_image').value = '';

            if (title) title.innerText = 'Add New Homepage Hero Slide';
            if (submitBtn) submitBtn.innerText = 'Add Slide';
            if (cancelBtn) cancelBtn.style.display = 'none';
        }

        // Testimonial Edit functions
        function editTestimonial(data) {
            const form = document.getElementById('testimonial-form');
            const title = document.getElementById('testimonial-form-title');
            const submitBtn = document.getElementById('btn-testimonial-submit');
            const cancelBtn = document.getElementById('btn-testimonial-cancel');

            if (!form) return;

            form.action = '/admin/testimonials/' + data.id + '/update';
            document.getElementById('testimonial_id').value = data.id;
            document.getElementById('test_name').value = data.name || '';
            document.getElementById('test_role').value = data.role || '';
            document.getElementById('test_rating').value = data.rating || 5;
            document.getElementById('test_sort').value = data.sort_order || 0;
            document.getElementById('test_avatar_url').value = data.avatar_path || '';
            document.getElementById('test_content').value = data.content || '';

            if (title) title.innerText = 'Edit Customer Testimonial: ' + (data.name || '');
            if (submitBtn) submitBtn.innerText = '💾 Save Testimonial Changes';
            if (cancelBtn) cancelBtn.style.display = 'inline-block';

            const card = document.getElementById('testimonial-form-card');
            if (card) card.scrollIntoView({ behavior: 'smooth' });
        }

        function resetTestimonialForm() {
            const form = document.getElementById('testimonial-form');
            const title = document.getElementById('testimonial-form-title');
            const submitBtn = document.getElementById('btn-testimonial-submit');
            const cancelBtn = document.getElementById('btn-testimonial-cancel');

            if (!form) return;

            form.action = "{{ route('admin.testimonials.store') }}";
            document.getElementById('testimonial_id').value = '';
            document.getElementById('test_name').value = '';
            document.getElementById('test_role').value = '';
            document.getElementById('test_rating').value = '5';
            document.getElementById('test_sort').value = '0';
            document.getElementById('test_avatar_url').value = '';
            document.getElementById('test_content').value = '';

            if (title) title.innerText = 'Add New Customer Testimonial';
            if (submitBtn) submitBtn.innerText = 'Add Testimonial';
            if (cancelBtn) cancelBtn.style.display = 'none';
        }

        // Service Edit functions
        function editService(data) {
            document.getElementById('service-form-title-text').innerText = 'Edit Service Page: ' + data.service_name;
            document.getElementById('service-ai-btn').style.display = 'none';
            document.getElementById('service-form').action = '/admin/services/' + data.id + '/update';
            document.getElementById('service-form-method').value = 'POST';
            
            document.getElementById('ser_service_name').value = data.service_name;
            document.getElementById('ser_slug').value = data.slug;
            document.getElementById('ser_badge').value = data.badge || '';
            document.getElementById('ser_sort_order').value = data.sort_order || 0;
            document.getElementById('ser_hero_category').value = data.hero_category || '';
            document.getElementById('ser_hero_title').value = data.hero_title || '';
            document.getElementById('ser_hero_subtitle').value = data.hero_subtitle || '';
            
            document.getElementById('ser_rate_value').value = data.rate_value || '';
            document.getElementById('ser_max_loan').value = data.max_loan || '';
            document.getElementById('ser_tenure').value = data.tenure || '';
            document.getElementById('ser_intro_title').value = data.intro_title || '';
            
            if (serviceIntroEditor) {
                serviceIntroEditor.setData(data.intro_content || '');
            } else {
                document.getElementById('ser_intro_content').value = data.intro_content || '';
            }
            
            document.getElementById('ser_eligibility_criteria').value = data.eligibility_criteria || '';
            document.getElementById('ser_documents_required').value = data.documents_required || '';
            document.getElementById('ser_tips_title').value = data.tips_title || '';
            
            if (serviceTipsEditor) {
                serviceTipsEditor.setData(data.tips_content || '');
            } else {
                document.getElementById('ser_tips_content').value = data.tips_content || '';
            }
            
            document.getElementById('ser_summary').value = data.summary || '';
            document.getElementById('ser_schema_markup').value = data.schema_markup || '';
            document.getElementById('ser_metatitle').value = data.metatitle || '';
            document.getElementById('ser_metadescription').value = data.metadescription || '';
            document.getElementById('ser_metakeywords').value = data.metakeywords || '';
            document.getElementById('ser_is_active').checked = !!data.is_active;
            document.getElementById('ser_image_url').value = (data.image_path && (data.image_path.startsWith('http') || data.image_path.startsWith('//'))) ? data.image_path : '';
            document.getElementById('ser_icon_url').value = (data.icon_path && (data.icon_path.startsWith('http') || data.icon_path.startsWith('//'))) ? data.icon_path : '';
            
            // Format FAQs for textarea
            let formattedFaqs = '';
            if (Array.isArray(data.faqs)) {
                formattedFaqs = data.faqs.map(f => (f.question || '') + ' | ' + (f.answer || '')).join('\n');
            } else if (typeof data.faqs === 'string') {
                try {
                    let parsed = JSON.parse(data.faqs);
                    if (Array.isArray(parsed)) {
                        formattedFaqs = parsed.map(f => (f.question || '') + ' | ' + (f.answer || '')).join('\n');
                    } else {
                        formattedFaqs = data.faqs;
                    }
                } catch(e) {
                    formattedFaqs = data.faqs;
                }
            }
            if (document.getElementById('ser_faqs')) {
                document.getElementById('ser_faqs').value = formattedFaqs;
            }
            
            // Clear and rebuild FAQs
            const container = document.getElementById('faq-repeater-container');
            container.innerHTML = '';
            
            let faqs = [];
            if (typeof data.faqs === 'string') {
                try {
                    faqs = JSON.parse(data.faqs);
                } catch(e) {
                    console.error("Failed to parse FAQs JSON string", e);
                }
            } else if (Array.isArray(data.faqs)) {
                faqs = data.faqs;
            }
            
            if (faqs && faqs.length > 0) {
                faqs.forEach(faq => {
                    addFaqInput(faq.question, faq.answer);
                });
            }
            
            document.getElementById('service-submit-btn').innerText = 'Update Service Page';
            document.getElementById('service-cancel-btn').style.display = 'inline-block';
            document.getElementById('service-form-container').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelServiceEdit() {
            document.getElementById('service-form-title-text').innerText = 'Create New Service Page';
            document.getElementById('service-ai-btn').style.display = 'inline-flex';
            document.getElementById('service-form').action = "{{ route('admin.services.store') }}";
            document.getElementById('service-form-method').value = 'POST';
            
            document.getElementById('ser_service_name').value = '';
            document.getElementById('ser_slug').value = '';
            document.getElementById('ser_badge').value = '';
            document.getElementById('ser_sort_order').value = 0;
            document.getElementById('ser_hero_category').value = '';
            document.getElementById('ser_hero_title').value = '';
            document.getElementById('ser_hero_subtitle').value = '';
            
            document.getElementById('ser_rate_value').value = '';
            document.getElementById('ser_max_loan').value = '';
            document.getElementById('ser_tenure').value = '';
            document.getElementById('ser_intro_title').value = '';
            
            if (serviceIntroEditor) {
                serviceIntroEditor.setData('');
            } else {
                document.getElementById('ser_intro_content').value = '';
            }
            
            document.getElementById('ser_eligibility_criteria').value = '';
            document.getElementById('ser_documents_required').value = '';
            document.getElementById('ser_tips_title').value = '';
            
            if (serviceTipsEditor) {
                serviceTipsEditor.setData('');
            } else {
                document.getElementById('ser_tips_content').value = '';
            }
            
            document.getElementById('ser_summary').value = '';
            if (document.getElementById('ser_faqs')) document.getElementById('ser_faqs').value = '';
            document.getElementById('ser_schema_markup').value = '';
            document.getElementById('ser_metatitle').value = '';
            document.getElementById('ser_metadescription').value = '';
            document.getElementById('ser_metakeywords').value = '';
            document.getElementById('ser_is_active').checked = true;
            document.getElementById('ser_image_url').value = '';
            document.getElementById('ser_icon_url').value = '';
            
            document.getElementById('faq-repeater-container').innerHTML = '';
            
            document.getElementById('service-submit-btn').innerText = 'Create Service Page';
            document.getElementById('service-cancel-btn').style.display = 'none';
        }


        // Bank Edit functions
        // Treatment Edit functions
        function editTreatment(data) {
            document.getElementById('treatment-form-title-text').innerText = 'Edit Treatment Page: ' + data.name;
            document.getElementById('treatment-form').action = '/admin/treatments/' + data.id + '/update';
            document.getElementById('treatment-form-method').value = 'POST';
            document.getElementById('treat_name').value = data.name;
            document.getElementById('treat_slug').value = data.slug;
            document.getElementById('treat_metatitle').value = data.metatitle || '';
            document.getElementById('treat_tags').value = data.tags || '';
            document.getElementById('treat_metadescription').value = data.metadescription || '';
            document.getElementById('treat_schema_markup').value = data.schema_markup || '';
            document.getElementById('treat_status').checked = !!data.status;
            
            if (treatmentEditor) {
                treatmentEditor.setData(data.description || '');
            } else {
                document.getElementById('treat_description').value = data.description || '';
            }
            
            // Format FAQs for treatment textarea
            let formattedTreatFaqs = '';
            if (Array.isArray(data.faqs)) {
                formattedTreatFaqs = data.faqs.map(f => (f.question || '') + ' | ' + (f.answer || '')).join('\n');
            } else if (typeof data.faqs === 'string') {
                try {
                    let parsed = JSON.parse(data.faqs);
                    if (Array.isArray(parsed)) {
                        formattedTreatFaqs = parsed.map(f => (f.question || '') + ' | ' + (f.answer || '')).join('\n');
                    } else {
                        formattedTreatFaqs = data.faqs;
                    }
                } catch(e) {
                    formattedTreatFaqs = data.faqs;
                }
            }
            if (document.getElementById('treat_faqs')) {
                document.getElementById('treat_faqs').value = formattedTreatFaqs;
            }
            
            document.getElementById('treat_image_url').value = (data.image && (data.image.startsWith('http') || data.image.startsWith('//'))) ? data.image : '';
            document.getElementById('treat-cancel-btn').style.display = 'inline-block';
            
            document.getElementById('treatment-form').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelTreatmentEdit() {
            document.getElementById('treatment-form-title-text').innerText = 'Add New Treatment Page';
            document.getElementById('treatment-form').action = "{{ route('admin.treatments.store') }}";
            document.getElementById('treatment-form-method').value = 'POST';
            document.getElementById('treat_name').value = '';
            document.getElementById('treat_slug').value = '';
            document.getElementById('treat_metatitle').value = '';
            document.getElementById('treat_tags').value = '';
            document.getElementById('treat_metadescription').value = '';
            document.getElementById('treat_schema_markup').value = '';
            document.getElementById('treat_status').checked = true;
            if (treatmentEditor) {
                treatmentEditor.setData('');
            } else {
                document.getElementById('treat_description').value = '';
            }
            if (document.getElementById('treat_faqs')) document.getElementById('treat_faqs').value = '';
            document.getElementById('treat_image_url').value = '';
            document.getElementById('treat-cancel-btn').style.display = 'none';
        }

        // Specialist Edit functions
        function editSpecialist(data) {
            document.getElementById('specialist-form-title-text').innerText = 'Edit Specialist: ' + data.name;
            document.getElementById('specialist-form').action = '/admin/specialists/' + data.id + '/update';
            document.getElementById('specialist-form-method').value = 'POST';
            document.getElementById('spec_name').value = data.name;
            document.getElementById('spec_designation').value = data.designation;
            document.getElementById('spec_slug').value = data.slug;
            document.getElementById('spec_phone').value = data.phone || '';
            document.getElementById('spec_facebook').value = data.facebook || '';
            document.getElementById('spec_twitter').value = data.twitter || '';
            document.getElementById('spec_metatitle').value = data.metatitle || '';
            document.getElementById('spec_tags').value = data.tags || '';
            document.getElementById('spec_metadescription').value = data.metadescription || '';
            document.getElementById('spec_schema_markup').value = data.schema_markup || '';
            document.getElementById('spec_status').checked = !!data.status;
            
            if (specialistEditor) {
                specialistEditor.setData(data.description || '');
            } else {
                document.getElementById('spec_description').value = data.description || '';
            }
            document.getElementById('spec_image_url').value = (data.image && (data.image.startsWith('http') || data.image.startsWith('//'))) ? data.image : '';
            document.getElementById('spec-cancel-btn').style.display = 'inline-block';
            
            document.getElementById('specialist-form').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelSpecialistEdit() {
            document.getElementById('specialist-form-title-text').innerText = 'Add New Clinic Specialist';
            document.getElementById('specialist-form').action = "{{ route('admin.specialists.store') }}";
            document.getElementById('specialist-form-method').value = 'POST';
            document.getElementById('spec_name').value = '';
            document.getElementById('spec_designation').value = '';
            document.getElementById('spec_slug').value = '';
            document.getElementById('spec_phone').value = '';
            document.getElementById('spec_facebook').value = '';
            document.getElementById('spec_twitter').value = '';
            document.getElementById('spec_metatitle').value = '';
            document.getElementById('spec_tags').value = '';
            document.getElementById('spec_metadescription').value = '';
            document.getElementById('spec_schema_markup').value = '';
            document.getElementById('spec_status').checked = true;
            if (specialistEditor) {
                specialistEditor.setData('');
            } else {
                document.getElementById('spec_description').value = '';
            }
            document.getElementById('spec_image_url').value = '';
            document.getElementById('spec-cancel-btn').style.display = 'none';
        }

        // CMS Page Edit functions
        function editCmsPage(data) {
            document.getElementById('cms-form-title-text').innerText = 'Edit CMS Page: ' + data.title;
            document.getElementById('cms-form').action = '/admin/cms-pages/' + data.id + '/update';
            document.getElementById('cms-form-method').value = 'POST';
            document.getElementById('cms_title').value = data.title;
            document.getElementById('cms_slug').value = data.slug;
            document.getElementById('cms_metatitle').value = data.metatitle || '';
            document.getElementById('cms_metakeywords').value = data.metakeywords || '';
            document.getElementById('cms_metadescription').value = data.metadescription || '';
            document.getElementById('cms_schema_markup').value = data.schema_markup || '';
            document.getElementById('cms_status').checked = !!data.status;
            
            if (cmsEditor) {
                cmsEditor.setData(data.description || '');
            } else {
                document.getElementById('cms_description').value = data.description || '';
            }
            document.getElementById('cms_image_url').value = (data.image && (data.image.startsWith('http') || data.image.startsWith('//'))) ? data.image : '';
            document.getElementById('cms-cancel-btn').style.display = 'inline-block';
            
            document.getElementById('cms-form').scrollIntoView({ behavior: 'smooth' });
        }

        function cancelCmsPageEdit() {
            document.getElementById('cms-form-title-text').innerText = 'Add New CMS Page';
            document.getElementById('cms-form').action = "{{ route('admin.cms_pages.store') }}";
            document.getElementById('cms-form-method').value = 'POST';
            document.getElementById('cms_title').value = '';
            document.getElementById('cms_slug').value = '';
            document.getElementById('cms_metatitle').value = '';
            document.getElementById('cms_metakeywords').value = '';
            document.getElementById('cms_metadescription').value = '';
            document.getElementById('cms_schema_markup').value = '';
            document.getElementById('cms_status').checked = true;
            if (cmsEditor) {
                cmsEditor.setData('');
            } else {
                document.getElementById('cms_description').value = '';
            }
            document.getElementById('cms_image_url').value = '';
            document.getElementById('cms-cancel-btn').style.display = 'none';
        }

        // Dummy bank methods to prevent errors
        function editBank(data) {}
        function cancelBankEdit() {}

        // Copy URL Helper
        function copyToClipboard(text, button) {
            navigator.clipboard.writeText(text).then(() => {
                const originalText = button.innerHTML;
                button.innerHTML = '<i data-lucide="check" style="width: 12px; height: 12px;"></i> Copied!';
                button.style.backgroundColor = 'var(--accent-teal)';
                button.style.color = '#fff';
                lucide.createIcons();
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.style.backgroundColor = '';
                    button.style.color = '';
                    lucide.createIcons();
                }, 2000);
            }).catch(err => {
                alert('Failed to copy link: ' + err);
            });
        }

        // AJAX Media Upload Helper
        function uploadMediaFile(input) {
            if (input.files.length === 0) return;
            
            const file = input.files[0];
            const formData = new FormData();
            formData.append('file', file);
            formData.append('_token', "{{ csrf_token() }}");
            
            const progressContainer = document.getElementById('media-upload-progress');
            const progressBar = document.getElementById('media-progress-bar');
            
            progressContainer.style.display = 'block';
            progressBar.style.width = '0%';
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', "{{ route('admin.media.upload') }}", true);
            
            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    const percentage = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentage + '%';
                }
            };
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        alert('Image uploaded and optimized to WebP successfully!');
                        location.reload();
                    } else {
                        alert('Upload failed: ' + (response.message || 'Unknown error'));
                    }
                } else {
                    alert('Upload failed with status ' + xhr.status);
                }
                progressContainer.style.display = 'none';
            };
            
            xhr.onerror = function() {
                alert('Network error during upload.');
                progressContainer.style.display = 'none';
            };
            
            xhr.send(formData);
        }

        // AI Generation UI & AJAX Helpers
        function openAiModal(type) {
            const overlay = document.getElementById('ai-' + type + '-modal');
            if (overlay) {
                overlay.style.display = 'flex';
                setTimeout(() => overlay.classList.add('show'), 10);
            }
        }

        function closeAiModal(type) {
            const overlay = document.getElementById('ai-' + type + '-modal');
            if (overlay) {
                overlay.classList.remove('show');
                setTimeout(() => {
                    overlay.style.display = 'none';
                    const formWrapper = document.getElementById('ai-' + type + '-form-wrapper');
                    const loader = document.getElementById('ai-' + type + '-loader');
                    if (formWrapper) formWrapper.style.display = 'block';
                    if (loader) loader.style.display = 'none';
                    
                    const promptInput = document.getElementById('ai_' + type + '_prompt');
                    if (promptInput) promptInput.value = '';

                    const previewBox = document.getElementById('ai-image-preview-container');
                    if (previewBox) previewBox.style.display = 'none';
                }, 300);
            }
        }

        // Toast Notification System
        function showToast(title, message, type = 'info', uniqueId = null) {
            const container = document.getElementById('ai-toast-container');
            if (!container) return null;

            let toast = uniqueId ? document.getElementById(uniqueId) : null;
            let isNew = false;

            if (!toast) {
                isNew = true;
                toast = document.createElement('div');
                toast.className = 'ai-toast';
                if (uniqueId) toast.id = uniqueId;
            }

            let iconHtml = '';
            if (type === 'info') {
                iconHtml = '<div class="ai-toast-spinner"></div>';
            } else if (type === 'success') {
                iconHtml = '<div class="ai-toast-success-icon"><i data-lucide="check-circle" style="width: 20px; height: 20px;"></i></div>';
            } else if (type === 'error') {
                iconHtml = '<div class="ai-toast-error-icon"><i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i></div>';
            }

            toast.innerHTML = `
                ${iconHtml}
                <div class="ai-toast-content">
                    <div class="ai-toast-title">${title}</div>
                    <p class="ai-toast-message">${message}</p>
                </div>
                <button class="ai-toast-close" onclick="this.parentElement.remove()">&times;</button>
            `;

            if (isNew) {
                container.appendChild(toast);
            }

            lucide.createIcons();

            if (Notification.permission === 'granted' && isNew) {
                try {
                    new Notification(title, { body: message });
                } catch(e) {}
            }

            if (type !== 'info') {
                setTimeout(() => {
                    if (toast && toast.parentElement) {
                        toast.style.transform = 'translateX(120%)';
                        toast.style.opacity = '0';
                        setTimeout(() => toast.remove(), 300);
                    }
                }, 6000);
            }

            return toast;
        }

        if (typeof Notification !== 'undefined' && Notification.permission === 'default') {
            Notification.requestPermission();
        }

        function generateLandingSlug(title) {
            document.getElementById('landing_slug').value = title.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
        }

        function toggleEditLandingForm(id) {
            const row = document.getElementById('landing-editor-' + id);
            if (row.style.display === 'none') {
                row.style.display = 'table-row';
            } else {
                row.style.display = 'none';
            }
        }

        function openLandingAiModal(id, title) {
            document.getElementById('ai_landing_id').value = id;
            document.getElementById('ai_landing_target_title').value = title;
            document.getElementById('ai_landing_prompt').value = 'Create an optimized promotional landing page for ' + title + ' highlighting specialized orthopedic procedures, patient care, recovery success rate, and booking online consultations.';
            openAiModal('landing');
        }


        function generateAiLandingPage() {
            const id = document.getElementById('ai_landing_id').value;
            const prompt = document.getElementById('ai_landing_prompt').value.trim();
            
            if (!prompt) {
                alert('Please enter a description or topic for the landing page.');
                return;
            }
            
            const formWrapper = document.getElementById('ai-landing-form-wrapper');
            const loader = document.getElementById('ai-landing-loader');
            
            formWrapper.style.display = 'none';
            loader.style.display = 'flex';
            
            const jobId = 'landing-gen-' + Date.now();
            showToast("Generating AI Landing Page", "Gemini is writing the landing page copy...", "info", jobId);
            
            fetch('{{ route("admin.ai.landing") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id: id,
                    prompt: prompt
                })
            })
            .then(response => response.json())
            .then(data => {
                closeAiModal('landing');
                if (data.success) {
                    showToast("Generation Successful", "Your landing page content is ready. Reloading...", "success", jobId);
                    setTimeout(() => window.location.reload(), 1200);
                } else {
                    alert('Error: ' + (data.error || 'Failed to generate content.'));
                }
            })
            .catch(error => {
                closeAiModal('landing');
                alert('Request failed: ' + error.message);
            });
        }

        function syncGmbReviews() {
            const btn = document.getElementById('btn-sync-gmb');
            const icon = document.getElementById('icon-sync-gmb');
            
            btn.disabled = true;
            btn.innerHTML = '<i data-lucide="loader" class="animate-spin" style="width: 16px; height: 16px;"></i> Syncing Reviews...';
            lucide.createIcons();
            
            const jobId = 'gmb-sync-' + Date.now();
            showToast("Syncing GMB Reviews", "Gemini is pulling reviews from your GMB profile link...", "info", jobId);
            
            fetch('{{ route("admin.ai.gmb-sync") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="refresh-cw" id="icon-sync-gmb" style="width: 16px; height: 16px;"></i> Sync GMB Reviews via AI';
                lucide.createIcons();
                
                if (data.success) {
                    showToast("Sync Successful", data.message, "success", jobId);
                    setTimeout(() => window.location.reload(), 1500);
                } else {
                    showToast("Sync Failed", data.error || 'Unknown error occurred.', "error", jobId);
                    alert('Error: ' + (data.error || 'Unknown error occurred.'));
                }
            })
            .catch(error => {
                btn.disabled = false;
                btn.innerHTML = '<i data-lucide="refresh-cw" id="icon-sync-gmb" style="width: 16px; height: 16px;"></i> Sync GMB Reviews via AI';
                lucide.createIcons();
                showToast("Request Failed", error.message, "error", jobId);
                alert('Request failed: ' + error.message);
            });
        }

        function generateAiBlog() {
            const prompt = document.getElementById('ai_blog_prompt').value.trim();
            const tone = document.getElementById('ai_blog_tone').value;
            const generateImage = document.getElementById('ai_blog_gen_image').checked;

            if (!prompt) {
                alert('Please enter a description or topic for the blog article.');
                return;
            }

            const formWrapper = document.getElementById('ai-blog-form-wrapper');
            const loader = document.getElementById('ai-blog-loader');

            formWrapper.style.display = 'none';
            loader.style.display = 'flex';

            let existingTitle = '';
            let existingSummary = '';
            let existingContent = '';
            const isEditing = document.getElementById('blog-cancel-btn').style.display !== 'none';
            if (isEditing) {
                existingTitle = document.getElementById('blog_title').value.trim();
                existingSummary = document.getElementById('blog_summary').value.trim();
                if (blogEditor) {
                    existingContent = blogEditor.getData().trim();
                } else {
                    existingContent = document.getElementById('blog_content').value.trim();
                }
            }

            // Generate blog text FIRST (without image to keep it fast!)
            fetch("{{ route('admin.ai.blog') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    prompt: prompt,
                    tone: tone,
                    generate_image: false,
                    existing_title: existingTitle,
                    existing_summary: existingSummary,
                    existing_content: existingContent
                })
            })

            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const data = res.data;
                    document.getElementById('blog_title').value = data.title || '';
                    document.getElementById('blog_category').value = data.category || 'Financial Tips';
                    document.getElementById('blog_summary').value = data.summary || '';
                    
                    if (blogEditor) {
                        blogEditor.setData(data.content || '');
                    } else {
                        document.getElementById('blog_content').value = data.content || '';
                    }

                    closeAiModal('blog');
                    
                    // Now, if generateImage was checked, kick off background cover image generation!
                    if (generateImage) {
                        const imgPrompt = data.image_prompt || prompt;
                        
                        showToast("Generating Blog Cover", "AI is rendering cover image in the background...", "info", "ai-blog-cover-job");
                        
                        fetch("{{ route('admin.ai.image') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
                            },
                            body: JSON.stringify({
                                prompt: imgPrompt,
                                aspect_ratio: "16:9"
                            })
                        })
                        .then(imgRes => imgRes.json())
                        .then(imgRes => {
                            if (imgRes.success && imgRes.url) {
                                document.getElementById('blog_image_url').value = imgRes.url;
                                showToast("Cover Image Attached!", "AI cover image generated and attached to blog form successfully.", "success", "ai-blog-cover-job");
                            } else {
                                showToast("Cover Image Failed", imgRes.error || "Failed to generate cover image. You can upload or paste a link manually.", "error", "ai-blog-cover-job");
                            }
                        })
                        .catch(err => {
                            showToast("Cover Image Failed", "Network error during cover image generation.", "error", "ai-blog-cover-job");
                        });
                    } else {
                        alert('AI generated draft successfully! Please review the form before publishing.');
                    }
                    
                    document.getElementById('blog-form-container').scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('AI Generation Error: ' + (res.error || 'Failed to generate blog article.'));
                    formWrapper.style.display = 'block';
                    loader.style.display = 'none';
                }
            })
            .catch(err => {
                alert('Network Error: ' + err.message);
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
            });
        }

        function generateAiService() {
            const prompt = document.getElementById('ai_service_prompt').value.trim();
            const tone = document.getElementById('ai_service_tone').value;

            if (!prompt) {
                alert('Please enter a description for the service page.');
                return;
            }

            const formWrapper = document.getElementById('ai-service-form-wrapper');
            const loader = document.getElementById('ai-service-loader');

            formWrapper.style.display = 'none';
            loader.style.display = 'flex';

            let existingName = '';
            let existingHeroTitle = '';
            let existingHeroSub = '';
            let existingIntro = '';
            let existingTips = '';
            let existingSummary = '';
            let existingFaqs = '';

            const isEditing = document.getElementById('service-cancel-btn').style.display !== 'none';
            if (isEditing) {
                existingName = document.getElementById('ser_service_name').value.trim();
                existingHeroTitle = document.getElementById('ser_hero_title').value.trim();
                existingHeroSub = document.getElementById('ser_hero_subtitle').value.trim();
                existingSummary = document.getElementById('ser_summary').value.trim();
                
                if (serviceIntroEditor) {
                    existingIntro = serviceIntroEditor.getData().trim();
                } else {
                    existingIntro = document.getElementById('ser_intro_content').value.trim();
                }
                
                if (serviceTipsEditor) {
                    existingTips = serviceTipsEditor.getData().trim();
                } else {
                    existingTips = document.getElementById('ser_tips_content').value.trim();
                }

                const faqItems = [];
                const questions = document.querySelectorAll('input[name="faq_questions[]"]');
                const answers = document.querySelectorAll('textarea[name="faq_answers[]"]');
                for (let i = 0; i < questions.length; i++) {
                    if (questions[i] && answers[i]) {
                        faqItems.push({
                            question: questions[i].value.trim(),
                            answer: answers[i].value.trim()
                        });
                    }
                }
                existingFaqs = JSON.stringify(faqItems);
            }

            fetch("{{ route('admin.ai.service') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    prompt: prompt,
                    tone: tone,
                    existing_service_name: existingName,
                    existing_hero_title: existingHeroTitle,
                    existing_hero_subtitle: existingHeroSub,
                    existing_intro_content: existingIntro,
                    existing_tips_content: existingTips,
                    existing_summary: existingSummary,
                    existing_faqs: existingFaqs
                })
            })

            .then(res => res.json())
            .then(res => {
                if (res.success && res.data) {
                    const data = res.data;
                    
                    document.getElementById('ser_service_name').value = data.service_name || '';
                    if (data.service_name) {
                        const slug = data.service_name.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
                        document.getElementById('ser_slug').value = slug;
                    }
                    
                    document.getElementById('ser_badge').value = data.badge || '';
                    document.getElementById('ser_hero_category').value = data.hero_category || '';
                    document.getElementById('ser_hero_title').value = data.hero_title || '';
                    document.getElementById('ser_hero_subtitle').value = data.hero_subtitle || '';
                    
                    document.getElementById('ser_rate_value').value = data.rate_value || '';
                    document.getElementById('ser_max_loan').value = data.max_loan || '';
                    document.getElementById('ser_tenure').value = data.tenure || '';
                    document.getElementById('ser_intro_title').value = data.intro_title || '';
                    
                    if (serviceIntroEditor) {
                        serviceIntroEditor.setData(data.intro_content || '');
                    } else {
                        document.getElementById('ser_intro_content').value = data.intro_content || '';
                    }
                    
                    document.getElementById('ser_eligibility_criteria').value = data.eligibility_criteria || '';
                    document.getElementById('ser_documents_required').value = data.documents_required || '';
                    document.getElementById('ser_tips_title').value = data.tips_title || '';
                    
                    if (serviceTipsEditor) {
                        serviceTipsEditor.setData(data.tips_content || '');
                    } else {
                        document.getElementById('ser_tips_content').value = data.tips_content || '';
                    }
                    
                    document.getElementById('ser_summary').value = data.summary || '';

                    // Clear existing FAQs
                    const faqContainer = document.getElementById('faq-repeater-container');
                    faqContainer.innerHTML = '';

                    // Populate FAQs
                    if (data.faqs && Array.isArray(data.faqs)) {
                        data.faqs.forEach(faq => {
                            addFaqInput(faq.question, faq.answer);
                        });
                    }

                    closeAiModal('service');
                    alert('AI generated service page details successfully! Please review the form before saving.');
                    document.getElementById('service-form-container').scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('AI Generation Error: ' + (res.error || 'Failed to generate service page.'));
                    formWrapper.style.display = 'block';
                    loader.style.display = 'none';
                }
            })
            .catch(err => {
                alert('Network Error: ' + err.message);
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
            });
        }

        function generateAiImage() {
            const prompt = document.getElementById('ai_image_prompt').value.trim();
            const ratio = document.getElementById('ai_image_aspect_ratio').value;

            if (!prompt) {
                alert('Please describe the image you want to generate.');
                return;
            }

            // Close modal immediately and run in background!
            closeAiModal('image');
            
            const jobId = "ai-image-job-" + Date.now();
            showToast("Generating AI Image", "Gemini is rendering your picture in the background...", "info", jobId);

            fetch("{{ route('admin.ai.image') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    prompt: prompt,
                    aspect_ratio: ratio
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success && res.url) {
                    const toast = showToast("AI Image Ready!", "Your generated image is ready and saved to the library.", "success", jobId);
                    
                    // Add Copy URL button directly in the background toast success notification!
                    const copyBtn = document.createElement('button');
                    copyBtn.className = 'btn-submit';
                    copyBtn.style = 'margin-top: 8px; font-size: 0.75rem; padding: 4px 8px; width: auto; display: inline-flex; align-items: center; gap: 4px;';
                    copyBtn.innerHTML = '<i data-lucide="copy" style="width: 12px; height: 12px;"></i> Copy URL';
                    const fullUrl = res.url.startsWith('http') || res.url.startsWith('//') || res.url.startsWith('/') 
                        ? res.url 
                        : ("{{ asset('storage') }}/" + res.url);
                    copyBtn.onclick = function() {
                        copyToClipboard(fullUrl, copyBtn);
                    };
                    
                    toast.querySelector('.ai-toast-content').appendChild(copyBtn);
                    lucide.createIcons();
                } else {
                    showToast("AI Generation Failed", res.error || "Failed to generate picture in background.", "error", jobId);
                }
            })
            .catch(err => {
                showToast("AI Generation Failed", "Network error during background image generation.", "error", jobId);
            });
        }

        function generateAiSlider() {
            const prompt = document.getElementById('ai_slider_prompt').value.trim();
            const genImg = document.getElementById('ai_slider_gen_image').checked;

            if (!prompt) {
                alert('Please describe the homepage slide theme or topic.');
                return;
            }

            const modal = document.getElementById('ai-slider-modal');
            const formWrapper = document.getElementById('ai-slider-form-wrapper');
            const loader = document.getElementById('ai-slider-loader');

            formWrapper.style.display = 'none';
            loader.style.display = 'block';

            fetch("{{ route('admin.ai.slider') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    prompt: prompt,
                    generate_image: genImg
                })
            })
            .then(res => res.json())
            .then(res => {
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
                closeAiModal('slider');

                if (res.success && res.data) {
                    const data = res.data;
                    document.getElementById('slide_title').value = data.title || '';
                    document.getElementById('slide_subtitle').value = data.subtitle || '';
                    document.getElementById('slide_btn_text').value = data.button_text || '';
                    document.getElementById('slide_btn_link').value = data.button_link || '';
                    if (data.image_url) {
                        document.getElementById('slide_image_url').value = data.image_url;
                    }
                    
                    showToast('Slide Content Generated!', 'Slide fields have been auto-populated. Please review and click Add Slide.', 'success');
                } else {
                    alert('Error: ' + (res.error || 'Failed to generate slide content.'));
                }
            })
            .catch(err => {
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
                closeAiModal('slider');
                alert('Request failed: ' + err.message);
            });
        }

        function generateAiCmsPage() {
            const prompt = document.getElementById('ai_cms_prompt').value.trim();
            if (!prompt) return alert('Please enter a CMS page topic.');
            const formWrapper = document.getElementById('ai-cms-form-wrapper');
            const loader = document.getElementById('ai-cms-loader');
            formWrapper.style.display = 'none';
            loader.style.display = 'block';

            fetch("{{ route('admin.ai.cms-page') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(res => res.json())
            .then(res => {
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
                closeAiModal('cms');
                if (res.success && res.data) {
                    if (document.getElementById('cms_title')) document.getElementById('cms_title').value = res.data.title || '';
                    if (document.getElementById('cms_slug')) document.getElementById('cms_slug').value = res.data.slug || '';
                    if (document.getElementById('cms_metatitle')) document.getElementById('cms_metatitle').value = res.data.metatitle || '';
                    if (document.getElementById('cms_metadescription')) document.getElementById('cms_metadescription').value = res.data.metadescription || '';
                    
                    if (window.cmsEditor) {
                        window.cmsEditor.setData(res.data.content || '');
                    }
                    const cmsArea = document.getElementById('cms_description') || document.getElementById('cms_content');
                    if (cmsArea) cmsArea.value = res.data.content || '';

                    showToast('CMS Page Generated!', 'Fields auto-populated. Click Save to create.', 'success');
                } else {
                    alert('Error: ' + (res.error || 'Failed to generate page.'));
                }
            })
            .catch(err => {
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
                closeAiModal('cms');
                alert('Request error: ' + err.message);
            });
        }

        function generateAiTreatment() {
            const prompt = document.getElementById('ai_treatment_prompt').value.trim();
            if (!prompt) return alert('Please enter a treatment name.');
            const formWrapper = document.getElementById('ai-treatment-form-wrapper');
            const loader = document.getElementById('ai-treatment-loader');
            formWrapper.style.display = 'none';
            loader.style.display = 'block';

            fetch("{{ route('admin.ai.treatment') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(res => res.json())
            .then(res => {
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
                closeAiModal('treatment');
                if (res.success && res.data) {
                    if (document.getElementById('treat_name')) document.getElementById('treat_name').value = res.data.name || '';
                    if (document.getElementById('treatment_name')) document.getElementById('treatment_name').value = res.data.name || '';

                    if (document.getElementById('treat_slug')) document.getElementById('treat_slug').value = res.data.slug || '';
                    if (document.getElementById('treatment_slug')) document.getElementById('treatment_slug').value = res.data.slug || '';

                    if (document.getElementById('treat_metatitle')) document.getElementById('treat_metatitle').value = res.data.metatitle || '';
                    if (document.getElementById('treatment_metatitle')) document.getElementById('treatment_metatitle').value = res.data.metatitle || '';

                    if (document.getElementById('treat_metadescription')) document.getElementById('treat_metadescription').value = res.data.metadescription || '';
                    if (document.getElementById('treatment_metadescription')) document.getElementById('treatment_metadescription').value = res.data.metadescription || '';

                    if (window.treatmentEditor) {
                        window.treatmentEditor.setData(res.data.description || '');
                    }
                    const treatDescArea = document.getElementById('treat_description') || document.getElementById('treatment_description');
                    if (treatDescArea) treatDescArea.value = res.data.description || '';

                    showToast('Treatment Generated!', 'Fields auto-populated. Click Save Treatment.', 'success');
                } else {
                    alert('Error: ' + (res.error || 'Failed to generate treatment.'));
                }
            })
            .catch(err => {
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
                closeAiModal('treatment');
                alert('Request error: ' + err.message);
            });
        }

        function generateAiSpecialist() {
            const prompt = document.getElementById('ai_specialist_prompt').value.trim();
            if (!prompt) return alert('Please enter specialist name & specialty.');
            const formWrapper = document.getElementById('ai-specialist-form-wrapper');
            const loader = document.getElementById('ai-specialist-loader');
            formWrapper.style.display = 'none';
            loader.style.display = 'block';

            fetch("{{ route('admin.ai.specialist') }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                body: JSON.stringify({ prompt: prompt })
            })
            .then(res => res.json())
            .then(res => {
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
                closeAiModal('specialist');
                if (res.success && res.data) {
                    if (document.getElementById('spec_name')) document.getElementById('spec_name').value = res.data.name || '';
                    if (document.getElementById('spec_slug')) document.getElementById('spec_slug').value = res.data.slug || '';
                    if (document.getElementById('spec_designation')) document.getElementById('spec_designation').value = res.data.designation || '';

                    if (window.specialistEditor) {
                        window.specialistEditor.setData(res.data.description || '');
                    }
                    const specArea = document.getElementById('spec_description') || document.getElementById('specialist_description');
                    if (specArea) specArea.value = res.data.description || '';

                    showToast('Specialist Generated!', 'Fields auto-populated. Click Save Specialist.', 'success');
                } else {
                    alert('Error: ' + (res.error || 'Failed to generate specialist profile.'));
                }
            })
            .catch(err => {
                formWrapper.style.display = 'block';
                loader.style.display = 'none';
                closeAiModal('specialist');
                alert('Request error: ' + err.message);
            });
        }

        // dynamic AI Schema generation helper
        function generateAiSchemaForType(type) {
            let id = null;
            let textarea = null;
            let schemaTypes = [];

            if (type === 'treatment') {
                textarea = document.getElementById('treat_schema_markup');
                const actionUrl = document.getElementById('treatment-form').action;
                const parts = actionUrl.split('/');
                // Action URL is e.g. /admin/treatments/{id}/update
                id = parts[parts.length - 2];
                if (isNaN(parseInt(id))) id = null;
                schemaTypes = ['MedicalCondition', 'MedicalProcedure'];
            } else if (type === 'specialist') {
                textarea = document.getElementById('spec_schema_markup');
                const actionUrl = document.getElementById('specialist-form').action;
                const parts = actionUrl.split('/');
                id = parts[parts.length - 2];
                if (isNaN(parseInt(id))) id = null;
                schemaTypes = ['Physician', 'MedicalBusiness'];
            } else if (type === 'cms_page') {
                textarea = document.getElementById('cms_schema_markup');
                const actionUrl = document.getElementById('cms-form').action;
                const parts = actionUrl.split('/');
                id = parts[parts.length - 2];
                if (isNaN(parseInt(id))) id = null;
                schemaTypes = ['WebPage'];
            }

            if (!textarea) return;

            // Show a simple loading indicator in textarea
            const originalVal = textarea.value;
            textarea.value = 'Generating Schema with Gemini AI... Please wait...';
            textarea.disabled = true;

            fetch('/admin/ai/generate-schema', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    type: type,
                    id: id,
                    schema_types: schemaTypes
                })
            })
            .then(res => res.json())
            .then(res => {
                textarea.disabled = false;
                if (res.success) {
                    textarea.value = res.schema;
                    showToast('AI Schema generated successfully!', 'success');
                } else {
                    textarea.value = originalVal;
                    alert('AI Generation failed: ' + (res.error || 'Unknown error'));
                }
            })
            .catch(err => {
                textarea.disabled = false;
                textarea.value = originalVal;
                alert('Network error generating schema: ' + err.message);
            });
        }
    </script>

    <!-- My Profile Settings Modal -->
    <div class="ai-modal-overlay" id="profile-modal" style="display: none;">
        <div class="ai-modal-card" style="border-color: rgba(92, 203, 179, 0.4); box-shadow: 0 10px 30px rgba(92, 203, 179, 0.15);">
            <button class="ai-modal-close" onclick="closeProfileModal()">&times;</button>
            <h3><i data-lucide="user"></i> My Profile Settings</h3>
            
            <form action="{{ route('admin.profile.update') }}" method="POST" style="margin-top: 1rem;">
                @csrf
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="profile_name">Full Name</label>
                    <input type="text" id="profile_name" name="name" class="form-control" value="{{ Auth::user() ? Auth::user()->name : '' }}" required>
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="profile_email">Email Address</label>
                    <input type="email" id="profile_email" name="email" class="form-control" value="{{ Auth::user() ? Auth::user()->email : '' }}" required>
                </div>
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label for="profile_password">New Password (leave blank to keep current)</label>
                    <input type="password" id="profile_password" name="password" class="form-control" placeholder="Minimum 6 characters">
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="profile_password_confirmation">Confirm New Password</label>
                    <input type="password" id="profile_password_confirmation" name="password_confirmation" class="form-control" placeholder="Re-type password">
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="closeProfileModal()">Cancel</button>
                    <button type="submit" class="btn-submit" style="background-color: var(--mint-green); color: #0d1718;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    @if($isSuper)
    <!-- Create Admin User Modal -->
    <div class="ai-modal-overlay" id="create-user-modal" style="display: none;">
        <div class="ai-modal-card" style="border-color: rgba(92, 203, 179, 0.4); box-shadow: 0 10px 30px rgba(92, 203, 179, 0.15); max-width: 650px;">
            <button class="ai-modal-close" onclick="closeCreateUserModal()">&times;</button>
            <h3><i data-lucide="user-plus"></i> Create New Admin User</h3>
            
            <form action="{{ route('admin.users.store') }}" method="POST" style="margin-top: 1rem;">
                @csrf
                <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="new_user_name">Full Name</label>
                        <input type="text" id="new_user_name" name="name" class="form-control" required placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label for="new_user_email">Email Address</label>
                        <input type="email" id="new_user_email" name="email" class="form-control" required placeholder="john@example.com">
                    </div>
                </div>
                
                <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="new_user_password">Password</label>
                        <input type="password" id="new_user_password" name="password" class="form-control" required placeholder="Minimum 6 characters">
                    </div>
                    <div class="form-group">
                        <label for="new_user_role">Role</label>
                        <select id="new_user_role" name="role" class="form-control" onchange="togglePermissionsCheckboxGrid('new', this.value)">
                            <option value="admin">Regular Admin</option>
                            <option value="superadmin">Super Admin (All Permissions)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" id="new-user-permissions-group" style="margin-bottom: 1.5rem;">
                    <label style="margin-bottom: 0.5rem; display: block;">Module Access Permissions</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; max-height: 200px; overflow-y: auto; padding: 10px; border: 1px solid var(--admin-border); border-radius: var(--radius-sm); background: rgba(0,0,0,0.2);">
                        @php
                            $modules = [
                                'general' => 'General Settings',
                                'leads' => 'Leads Manager',
                                'livechat' => 'Live Chat Support',
                                'branding' => 'Branding & Logos',
                                'slider' => 'Homepage Slider',
                                'testimonials' => 'Testimonials',
                                'styling' => 'Section Backgrounds',
                                'pagecontents' => 'Page Editor',
                                'landing' => 'AI Landing Pages',
                                'services' => 'Services Manager',
                                'treatments' => 'Treatments Manager',
                                'specialists' => 'Specialists Manager',
                                'cms_pages' => 'CMS Pages Manager',
                                'blogs' => 'Blog Manager',
                                'media' => 'Media Manager',
                                'seo' => 'SEO Optimizer'
                            ];
                        @endphp
                        @foreach($modules as $key => $label)
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; cursor: pointer; color: white;">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" class="new-user-perm-cb" checked style="width: 14px; height: 14px; accent-color: var(--mint-green);">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="closeCreateUserModal()">Cancel</button>
                    <button type="submit" class="btn-submit" style="background-color: var(--mint-green); color: #0d1718;">Create User</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Admin User Modal -->
    <div class="ai-modal-overlay" id="edit-user-modal" style="display: none;">
        <div class="ai-modal-card" style="border-color: rgba(92, 203, 179, 0.4); box-shadow: 0 10px 30px rgba(92, 203, 179, 0.15); max-width: 650px;">
            <button class="ai-modal-close" onclick="closeEditUserModal()">&times;</button>
            <h3><i data-lucide="edit"></i> Edit Admin User</h3>
            
            <form id="edit-user-form" action="" method="POST" style="margin-top: 1rem;">
                @csrf
                <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="edit_user_name">Full Name</label>
                        <input type="text" id="edit_user_name" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_user_email">Email Address</label>
                        <input type="email" id="edit_user_email" name="email" class="form-control" required>
                    </div>
                </div>
                
                <div class="form-grid" style="grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label for="edit_user_password">Password (leave blank to keep current)</label>
                        <input type="password" id="edit_user_password" name="password" class="form-control" placeholder="Minimum 6 characters">
                    </div>
                    <div class="form-group">
                        <label for="edit_user_role">Role</label>
                        <select id="edit_user_role" name="role" class="form-control" onchange="togglePermissionsCheckboxGrid('edit', this.value)">
                            <option value="admin">Regular Admin</option>
                            <option value="superadmin">Super Admin (All Permissions)</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group" id="edit-user-permissions-group" style="margin-bottom: 1.5rem;">
                    <label style="margin-bottom: 0.5rem; display: block;">Module Access Permissions</label>
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; max-height: 200px; overflow-y: auto; padding: 10px; border: 1px solid var(--admin-border); border-radius: var(--radius-sm); background: rgba(0,0,0,0.2);">
                        @foreach($modules as $key => $label)
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 0.8rem; cursor: pointer; color: white;">
                                <input type="checkbox" name="permissions[]" value="{{ $key }}" id="edit_perm_{{ $key }}" class="edit-user-perm-cb" style="width: 14px; height: 14px; accent-color: var(--mint-green);">
                                {{ $label }}
                            </label>
                        @endforeach
                    </div>
                </div>
                
                <div style="display: flex; justify-content: flex-end; gap: 10px;">
                    <button type="button" class="btn-submit" style="background-color: var(--admin-input-bg); color: #fff;" onclick="closeEditUserModal()">Cancel</button>
                    <button type="submit" class="btn-submit" style="background-color: var(--mint-green); color: #0d1718;">Update User</button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <script>
        function openProfileModal() {
            const modal = document.getElementById('profile-modal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }
        function closeProfileModal() {
            const modal = document.getElementById('profile-modal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
        }
        
        @if($isSuper)
        function openCreateUserModal() {
            const modal = document.getElementById('create-user-modal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }
        function closeCreateUserModal() {
            const modal = document.getElementById('create-user-modal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
        }
        function openEditUserModal(userJson) {
            const user = JSON.parse(userJson);
            document.getElementById('edit-user-form').action = '/admin/users/' + user.id + '/update';
            document.getElementById('edit_user_name').value = user.name;
            document.getElementById('edit_user_email').value = user.email;
            document.getElementById('edit_user_password').value = '';
            document.getElementById('edit_user_role').value = user.role;
            
            // Clear all checkboxes
            const checkboxes = document.querySelectorAll('.edit-user-perm-cb');
            checkboxes.forEach(cb => cb.checked = false);
            
            // Set checkboxes based on user permissions
            let permissions = user.permissions || [];
            if (typeof permissions === 'string') {
                try {
                    permissions = JSON.parse(permissions);
                } catch(e) {
                    permissions = [];
                }
            }
            if (user.role === 'superadmin' || permissions.includes('all')) {
                checkboxes.forEach(cb => cb.checked = true);
            } else {
                permissions.forEach(p => {
                    const cb = document.getElementById('edit_perm_' + p);
                    if (cb) cb.checked = true;
                });
            }
            
            togglePermissionsCheckboxGrid('edit', user.role);

            const modal = document.getElementById('edit-user-modal');
            modal.style.display = 'flex';
            setTimeout(() => modal.classList.add('show'), 10);
        }
        function closeEditUserModal() {
            const modal = document.getElementById('edit-user-modal');
            modal.classList.remove('show');
            setTimeout(() => modal.style.display = 'none', 300);
        }
        function togglePermissionsCheckboxGrid(prefix, role) {
            const container = document.getElementById(prefix + '-user-permissions-group');
            if (!container) return;
            if (role === 'superadmin') {
                container.style.opacity = '0.5';
                container.style.pointerEvents = 'none';
                const checkboxes = document.querySelectorAll('.' + prefix + '-user-perm-cb');
                checkboxes.forEach(cb => cb.checked = true);
            } else {
                container.style.opacity = '1';
                container.style.pointerEvents = 'auto';
            }
        }
        @endif

        function handleBroadcastTypeChange(type) {
            const types = ['blog', 'service', 'cms_page', 'landing', 'specialist'];
            types.forEach(t => {
                const el = document.getElementById('group-select-' + t);
                if (el) el.style.display = 'none';
            });
            
            document.getElementById('broadcast_title').value = '';
            document.getElementById('broadcast_message').value = '';
            document.getElementById('broadcast_url').value = '';
            
            if (type !== 'custom') {
                const group = document.getElementById('group-select-' + type);
                if (group) {
                    group.style.display = 'block';
                    document.getElementById('select_' + type).value = '';
                }
            }
            updateLivePreview();
        }

        function autoFillFromEntity(type, id) {
            if (!id) {
                document.getElementById('broadcast_title').value = '';
                document.getElementById('broadcast_message').value = '';
                document.getElementById('broadcast_url').value = '';
                updateLivePreview();
                return;
            }
            const select = document.getElementById('select_' + type);
            const option = select.options[select.selectedIndex];
            
            const title = option.getAttribute('data-title') || '';
            const summary = option.getAttribute('data-summary') || '';
            const url = option.getAttribute('data-url') || '';
            
            document.getElementById('broadcast_title').value = title;
            document.getElementById('broadcast_message').value = summary;
            document.getElementById('broadcast_url').value = url;
            
            updateLivePreview();
        }

        function updateLivePreview() {
            const title = document.getElementById('broadcast_title').value || 'Tenderfold';
            const message = document.getElementById('broadcast_message').value || 'Waiting for input details...';
            const url = document.getElementById('broadcast_url').value || 'https://tenderfold.in';
            
            document.getElementById('preview-title').textContent = title;
            document.getElementById('preview-message').textContent = message;
            document.getElementById('preview-url').textContent = url;
        }

        // Submenu accordion toggle
        function toggleSubmenu(el) {
            const parent = el.closest('.has-submenu');
            const list = parent.querySelector('.submenu-list');
            const arrow = parent.querySelector('.submenu-arrow');
            const isOpen = list.style.display === 'block';
            
            list.style.display = isOpen ? 'none' : 'block';
            if (arrow) arrow.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(180deg)';
            if (isOpen) {
                parent.classList.remove('open');
            } else {
                parent.classList.add('open');
            }
        }

        // Test AI Connection & Model
        function testAiConnection() {
            const apiKey = document.getElementById('gemini_api_key').value.trim();
            const model = document.getElementById('ai_model').value;
            const btn = document.getElementById('btn-test-ai');
            const origHtml = btn.innerHTML;

            btn.disabled = true;
            btn.innerHTML = `<i data-lucide="loader" class="spin" style="width: 14px; height: 14px; vertical-align: middle;"></i> Testing...`;
            if (typeof lucide !== 'undefined') lucide.createIcons();

            fetch('{{ route("admin.ai.test-connection") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ api_key: apiKey, model: model })
            })
            .then(r => r.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = origHtml;
                if (data.success) {
                    if (typeof showToast === 'function') showToast(data.message, 'success');
                    else alert(data.message);
                } else {
                    if (typeof showToast === 'function') showToast(data.message, 'error');
                    else alert(data.message);
                }
            })
            .catch(err => {
                btn.disabled = false;
                btn.innerHTML = origHtml;
                if (typeof showToast === 'function') showToast('Connection Error: ' + err.message, 'error');
                else alert('Connection Error: ' + err.message);
            });
        }

        // Bulk Delete Leads Form Handler
        function submitBulkDelete() {
            const checkedBoxes = Array.from(document.querySelectorAll('.lead-checkbox')).filter(cb => cb.checked);
            if (checkedBoxes.length === 0) return;
            if (!confirm(`Are you sure you want to delete ${checkedBoxes.length} selected lead record(s)?`)) return;

            const inputsContainer = document.getElementById('bulk-delete-inputs');
            inputsContainer.innerHTML = '';
            checkedBoxes.forEach(cb => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'ids[]';
                hiddenInput.value = cb.value;
                inputsContainer.appendChild(hiddenInput);
            });

            document.getElementById('bulk-delete-form').submit();
        }

        // Internal Notifications JS Engine
        let currentNotifFilter = 'all';

        function toggleNotificationDropdown() {
            const drop = document.getElementById('nav-bell-dropdown');
            const isVisible = drop.style.display === 'block';
            drop.style.display = isVisible ? 'none' : 'block';
            if (!isVisible) {
                fetchInternalNotifications();
            }
        }

        function fetchInternalNotifications() {
            fetch(`/admin/internal-notifications?type=${currentNotifFilter}`)
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderInternalNotifications(data.notifications, data.unread_count);
                    }
                })
                .catch(err => console.error('Error fetching internal notifications:', err));
        }

        function renderInternalNotifications(notifications, unreadCount) {
            const countBadge = document.getElementById('nav-bell-count');
            const tabBadge = document.getElementById('admin-internal-unread-badge');
            
            if (unreadCount > 0) {
                countBadge.textContent = unreadCount;
                countBadge.style.display = 'inline-block';
                if (tabBadge) {
                    tabBadge.textContent = unreadCount;
                    tabBadge.style.display = 'inline-flex';
                }
            } else {
                countBadge.style.display = 'none';
                if (tabBadge) tabBadge.style.display = 'none';
            }

            // Bell popover list
            const bellList = document.getElementById('nav-bell-items-list');
            if (bellList) {
                if (notifications.length === 0) {
                    bellList.innerHTML = `<div style="padding: 1.5rem; text-align: center; color: var(--admin-text-muted); font-size: 0.8rem;">No recent notifications</div>`;
                } else {
                    bellList.innerHTML = '';
                    notifications.slice(0, 6).forEach(n => {
                        const div = document.createElement('div');
                        div.style.padding = '0.75rem 1rem';
                        div.style.borderBottom = '1px solid var(--admin-border)';
                        div.style.backgroundColor = n.is_read ? 'transparent' : 'rgba(92, 203, 179, 0.05)';
                        div.style.fontSize = '0.8rem';
                        div.innerHTML = `
                            <div style="display: flex; justify-content: space-between; margin-bottom: 2px;">
                                <strong style="color: ${n.type === 'error' ? '#f87171' : (n.type === 'lead' ? '#f59e0b' : 'var(--mint-green)')}; font-size: 0.82rem;">${escapeHTML(n.title)}</strong>
                                <span style="font-size: 0.7rem; color: #94a3b8;">${new Date(n.created_at).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}</span>
                            </div>
                            <div style="color: #cbd5e1; margin-bottom: 4px;">${escapeHTML(n.message || '')}</div>
                            <div style="font-size: 0.7rem; color: #64748b;">By: ${escapeHTML(n.user_name || 'System')}</div>
                        `;
                        bellList.appendChild(div);
                    });
                }
            }

            // Full Tab Table List
            const tabWrap = document.getElementById('internal-notifications-table-wrap');
            if (tabWrap) {
                if (notifications.length === 0) {
                    tabWrap.innerHTML = `<div style="padding: 3rem; text-align: center; color: var(--admin-text-muted); font-size: 0.9rem;">No system notifications recorded in this filter category.</div>`;
                } else {
                    let html = `
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Details / Message</th>
                                    <th>Triggered By</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                    `;
                    notifications.forEach(n => {
                        const typeBg = n.type === 'error' ? '#fee2e2' : (n.type === 'lead' ? '#fef3c7' : (n.type === 'chat' ? '#d1fae5' : '#dbeafe'));
                        const typeFg = n.type === 'error' ? '#991b1b' : (n.type === 'lead' ? '#92400e' : (n.type === 'chat' ? '#065f46' : '#1e40af'));
                        const timeStr = new Date(n.created_at).toLocaleString();

                        html += `
                            <tr style="background-color: ${n.is_read ? 'transparent' : 'rgba(92, 203, 179, 0.04)'};">
                                <td style="font-size: 0.8rem; color: #94a3b8; white-space: nowrap;">${timeStr}</td>
                                <td><span class="badge" style="background-color: ${typeBg}; color: ${typeFg}; text-transform: uppercase; font-size: 0.7rem;">${escapeHTML(n.type)}</span></td>
                                <td><strong style="color: white; font-size: 0.9rem;">${escapeHTML(n.title)}</strong></td>
                                <td style="font-size: 0.85rem; color: #cbd5e1;">${escapeHTML(n.message || '-')}</td>
                                <td style="font-size: 0.8rem; color: var(--mint-green);">${escapeHTML(n.user_name || 'System')}</td>
                                <td>
                                    ${n.action_url ? `<a href="${n.action_url}" class="btn" style="padding: 4px 10px; font-size: 0.75rem; background: rgba(255,255,255,0.08); color: white; text-decoration: none; border-radius: 4px;">Open Link →</a>` : '-'}
                                </td>
                            </tr>
                        `;
                    });
                    html += `</tbody></table>`;
                    tabWrap.innerHTML = html;
                }
            }
        }

        function filterInternalNotifications(type) {
            currentNotifFilter = type;
            document.querySelectorAll('.btn-filter-notif').forEach(btn => {
                btn.classList.remove('active');
                btn.style.backgroundColor = 'var(--admin-input-bg)';
                btn.style.color = '#cbd5e1';
                btn.style.border = '1px solid var(--admin-border)';
            });
            const activeBtn = document.getElementById('notif-filter-' + type);
            if (activeBtn) {
                activeBtn.classList.add('active');
                activeBtn.style.backgroundColor = 'var(--primary-teal)';
                activeBtn.style.color = 'white';
                activeBtn.style.border = 'none';
            }
            fetchInternalNotifications();
        }

        function markAllNotificationsRead() {
            fetch('{{ route("admin.internal-notifications.mark-read") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    fetchInternalNotifications();
                    if (typeof showToast === 'function') showToast('All notifications marked as read', 'success');
                }
            });
        }

        function clearAllNotifications() {
            if (!confirm('Are you sure you want to clear all internal notification records?')) return;
            fetch('{{ route("admin.internal-notifications.clear") }}', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    fetchInternalNotifications();
                    if (typeof showToast === 'function') showToast('Notification log cleared', 'success');
                }
            });
        }

        // Periodically check internal notifications in background (every 60s when tab is active)
        setInterval(() => {
            if (!document.hidden) {
                fetchInternalNotifications();
            }
        }, 60000);
        setTimeout(fetchInternalNotifications, 1000);

        // Background poll for live chat sessions (every 45s when tab is active)
        setInterval(() => {
            if (!document.hidden && typeof loadAdminChatSessions === 'function') {
                loadAdminChatSessions();
            }
        }, 45000);
    </script>

</body>
</html>

