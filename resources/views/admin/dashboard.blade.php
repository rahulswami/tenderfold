<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CMS Admin Dashboard | MLG Finedge</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <!-- Outfit Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --admin-sidebar-w: 260px;
            --admin-dark-bg: #111b1c;
            --admin-dark-sidebar: #0a1112;
            --admin-border: rgba(255,255,255,0.08);
            --admin-text-muted: rgba(255,255,255,0.5);
            --admin-input-bg: rgba(255,255,255,0.04);
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

        .menu-item button {
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
        }

        .menu-item button:hover, .menu-item button.active {
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
            flex-grow: 1;
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
            overflow-y: auto;
            flex-grow: 1;
        }

        /* Tab Content Panel */
        .tab-panel {
            display: none;
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
        <div class="sidebar-brand">
            <svg viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="25" y="45" width="12" height="30" rx="3" fill="#ffffff" />
                <rect x="44" y="30" width="12" height="45" rx="3" fill="#ffffff" />
                <rect x="63" y="15" width="12" height="60" rx="3" fill="#5ccbb3" />
                <path d="M15 80 L35 60 L50 70 L85 35" stroke="#e85c24" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M72 35 H85 V48" stroke="#e85c24" stroke-width="6" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <span>MLG FINEDGE</span>
            <button class="sidebar-close-btn" onclick="toggleSidebar()" aria-label="Close Sidebar">
                <i data-lucide="x"></i>
            </button>
        </div>
        
        <ul class="sidebar-menu">
            <li class="menu-item"><button onclick="switchTab('general')" class="tab-btn" id="btn-general"><i data-lucide="settings"></i> General Settings</button></li>
            <li class="menu-item"><button onclick="switchTab('leads')" class="tab-btn active" id="btn-leads"><i data-lucide="inbox"></i> Leads Manager</button></li>
            <li class="menu-item">
                <button onclick="switchTab('livechat')" class="tab-btn" id="btn-livechat" style="position: relative;">
                    <i data-lucide="message-square"></i> Live Chat Support
                    <span id="admin-chat-total-badge" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); background-color: #ef4444; color: white; font-size: 0.65rem; font-weight: 700; width: 18px; height: 18px; border-radius: 50%; display: none; align-items: center; justify-content: center;">0</span>
                </button>
            </li>
            <li class="menu-item"><button onclick="switchTab('branding')" class="tab-btn" id="btn-branding"><i data-lucide="image"></i> Branding & Logos</button></li>
            <li class="menu-item"><button onclick="switchTab('slider')" class="tab-btn" id="btn-slider"><i data-lucide="sliders"></i> Homepage Slider</button></li>
            <li class="menu-item"><button onclick="switchTab('testimonials')" class="tab-btn" id="btn-testimonials"><i data-lucide="users"></i> Testimonials</button></li>
            <li class="menu-item"><button onclick="switchTab('styling')" class="tab-btn" id="btn-styling"><i data-lucide="palette"></i> Section Backgrounds</button></li>
            <li class="menu-item"><button onclick="switchTab('pagecontents')" class="tab-btn" id="btn-pagecontents"><i data-lucide="file-text"></i> Page Editor</button></li>
            <li class="menu-item"><button onclick="switchTab('landing')" class="tab-btn" id="btn-landing"><i data-lucide="layout"></i> AI Landing Pages</button></li>
            <li class="menu-item"><button onclick="switchTab('services')" class="tab-btn" id="btn-services"><i data-lucide="layers"></i> Services Manager</button></li>
            <li class="menu-item"><button onclick="switchTab('treatments')" class="tab-btn" id="btn-treatments"><i data-lucide="activity"></i> Treatments Manager</button></li>
            <li class="menu-item"><button onclick="switchTab('specialists')" class="tab-btn" id="btn-specialists"><i data-lucide="user-check"></i> Specialists Manager</button></li>
            <li class="menu-item"><button onclick="switchTab('cms_pages')" class="tab-btn" id="btn-cms_pages"><i data-lucide="files"></i> CMS Pages Manager</button></li>
            <li class="menu-item"><button onclick="switchTab('blogs')" class="tab-btn" id="btn-blogs"><i data-lucide="book-open"></i> Blog Manager</button></li>
            <li class="menu-item"><button onclick="switchTab('media')" class="tab-btn" id="btn-media"><i data-lucide="hard-drive"></i> Media Manager</button></li>
            <li class="menu-item"><button onclick="switchTab('analytics')" class="tab-btn" id="btn-analytics"><i data-lucide="bar-chart-2"></i> Web Analytics</button></li>
            <li class="menu-item"><button onclick="switchTab('seo')" class="tab-btn" id="btn-seo"><i data-lucide="trending-up"></i> SEO Optimizer</button></li>
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
            <div style="display: flex; gap: 1.5rem; align-items: center;">
                <div class="admin-user">
                    <span>{{ Auth::user()->name }}</span>
                    <div class="admin-user-avatar">{{ substr(Auth::user()->name, 0, 1) }}</div>
                </div>
            </div>
        </div>

        <!-- Mobile Quick-Switch Tabs -->
        <div class="mobile-tabs-scroll">
            <button onclick="switchTab('general')" class="mobile-tab-btn" id="m-btn-general"><i data-lucide="settings" style="width: 14px; height: 14px;"></i> General</button>
            <button onclick="switchTab('leads')" class="mobile-tab-btn" id="m-btn-leads"><i data-lucide="inbox" style="width: 14px; height: 14px;"></i> Leads</button>
            <button onclick="switchTab('livechat')" class="mobile-tab-btn" id="m-btn-livechat" style="position: relative;"><i data-lucide="message-square" style="width: 14px; height: 14px;"></i> Live Chat <span id="admin-chat-mobile-badge" style="position: absolute; right: 2px; top: 2px; background-color: #ef4444; color: white; font-size: 0.6rem; font-weight: 700; width: 14px; height: 14px; border-radius: 50%; display: none; align-items: center; justify-content: center;">0</span></button>
            <button onclick="switchTab('branding')" class="mobile-tab-btn" id="m-btn-branding"><i data-lucide="image" style="width: 14px; height: 14px;"></i> Branding</button>
            <button onclick="switchTab('slider')" class="mobile-tab-btn" id="m-btn-slider"><i data-lucide="sliders" style="width: 14px; height: 14px;"></i> Slider</button>
            <button onclick="switchTab('testimonials')" class="mobile-tab-btn" id="m-btn-testimonials"><i data-lucide="users" style="width: 14px; height: 14px;"></i> Testimonials</button>
            <button onclick="switchTab('styling')" class="mobile-tab-btn" id="m-btn-styling"><i data-lucide="palette" style="width: 14px; height: 14px;"></i> Styling</button>
            <button onclick="switchTab('pagecontents')" class="mobile-tab-btn" id="m-btn-pagecontents"><i data-lucide="file-text" style="width: 14px; height: 14px;"></i> Pages</button>
            <button onclick="switchTab('landing')" class="mobile-tab-btn" id="m-btn-landing"><i data-lucide="layout" style="width: 14px; height: 14px;"></i> AI Pages</button>
            <button onclick="switchTab('services')" class="mobile-tab-btn" id="m-btn-services"><i data-lucide="layers" style="width: 14px; height: 14px;"></i> Services</button>
            <button onclick="switchTab('treatments')" class="mobile-tab-btn" id="m-btn-treatments"><i data-lucide="activity" style="width: 14px; height: 14px;"></i> Treatments</button>
            <button onclick="switchTab('specialists')" class="mobile-tab-btn" id="m-btn-specialists"><i data-lucide="user-check" style="width: 14px; height: 14px;"></i> Specialists</button>
            <button onclick="switchTab('cms_pages')" class="mobile-tab-btn" id="m-btn-cms_pages"><i data-lucide="files" style="width: 14px; height: 14px;"></i> CMS Pages</button>
            <button onclick="switchTab('blogs')" class="mobile-tab-btn" id="m-btn-blogs"><i data-lucide="book-open" style="width: 14px; height: 14px;"></i> Blogs</button>
            <button onclick="switchTab('media')" class="mobile-tab-btn" id="m-btn-media"><i data-lucide="hard-drive" style="width: 14px; height: 14px;"></i> Media</button>
            <button onclick="switchTab('analytics')" class="mobile-tab-btn" id="m-btn-analytics"><i data-lucide="bar-chart-2" style="width: 14px; height: 14px;"></i> Analytics</button>
            <button onclick="switchTab('seo')" class="mobile-tab-btn" id="m-btn-seo"><i data-lucide="trending-up" style="width: 14px; height: 14px;"></i> SEO</button>
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
            <div class="tab-panel" id="tab-general">
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
                        <h2>Social Media Profiles</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">Add links to your official social media pages to show them in the footer.</p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="social_facebook">Facebook URL</label>
                                <input type="url" id="social_facebook" name="social_facebook" class="form-control" value="{{ $site['social_facebook'] ?? '' }}" placeholder="https://facebook.com/yourpage">
                            </div>
                            <div class="form-group">
                                <label for="social_instagram">Instagram URL</label>
                                <input type="url" id="social_instagram" name="social_instagram" class="form-control" value="{{ $site['social_instagram'] ?? '' }}" placeholder="https://instagram.com/yourusername">
                            </div>
                            <div class="form-group">
                                <label for="social_linkedin">LinkedIn URL</label>
                                <input type="url" id="social_linkedin" name="social_linkedin" class="form-control" value="{{ $site['social_linkedin'] ?? '' }}" placeholder="https://linkedin.com/company/yourcompany">
                            </div>
                            <div class="form-group">
                                <label for="social_twitter">Twitter / X URL</label>
                                <input type="url" id="social_twitter" name="social_twitter" class="form-control" value="{{ $site['social_twitter'] ?? '' }}" placeholder="https://twitter.com/yourhandle">
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
                                <input type="password" id="gemini_api_key" name="gemini_api_key" class="form-control" value="{{ $site['gemini_api_key'] ?? '' }}" placeholder="Paste your Gemini API Key here...">
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted); display: block; margin-top: 5px;">
                                    Get an API Key from the <a href="https://aistudio.google.com/" target="_blank" style="color: var(--mint-green); text-decoration: underline;">Google AI Studio Console</a>.
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
                                <input type="text" id="cloudflare_r2_public_url" name="cloudflare_r2_public_url" class="form-control" value="{{ $site['cloudflare_r2_public_url'] ?? '' }}" placeholder="e.g., https://assets.mlgfinedge.com">
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
                                <input type="text" id="live_site_url" name="live_site_url" class="form-control" value="{{ $site['live_site_url'] ?? 'https://mlgfinedge.com' }}" placeholder="e.g., https://mlgfinedge.com">
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
                                <input type="email" id="smtp_from_address" name="smtp_from_address" class="form-control" value="{{ $site['smtp_from_address'] ?? 'no-reply@mlgfinedge.com' }}" placeholder="no-reply@mlgfinedge.com">
                            </div>
                            <div class="form-group">
                                <label for="smtp_from_name">From Sender Name</label>
                                <input type="text" id="smtp_from_name" name="smtp_from_name" class="form-control" value="{{ $site['smtp_from_name'] ?? 'MLG Finedge Alerts' }}" placeholder="MLG Finedge Alerts">
                            </div>
                            <div class="form-group">
                                <label for="smtp_to_email">Recipient Email Address (Admin Alert)</label>
                                <input type="email" id="smtp_to_email" name="smtp_to_email" class="form-control" value="{{ $site['smtp_to_email'] ?? 'admin@mlgfinedge.com' }}" placeholder="admin@mlgfinedge.com">
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
            <div class="tab-panel active" id="tab-leads">
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
                        <form id="bulk-delete-form" action="{{ route('admin.leads.bulk-delete') }}" method="POST" style="margin: 0;">
                            @csrf
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
                                                    <input type="checkbox" name="ids[]" value="{{ $lead->id }}" class="lead-checkbox" onchange="updateBulkDeleteState()" style="cursor: pointer;">
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
                                                    <button type="button" onclick="toggleLeadDetails({{ $lead->id }})" class="btn-action edit" title="View Details / Edit" style="display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border-radius: 4px; font-weight: 500; font-size: 0.85rem; border: 1px solid var(--primary-teal); background-color: transparent; color: var(--primary-teal);">
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
                                                            @if($lead->notes)
                                                                <div style="margin-top: 1rem;">
                                                                    <h5 style="margin: 0 0 0.5rem 0; color: rgba(255,255,255,0.6); font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.5px;">Admin Notes History</h5>
                                                                    <p style="font-size: 0.85rem; color: rgba(255,255,255,0.85); font-style: italic; background-color: rgba(255,255,255,0.02); padding: 0.50rem 0.75rem; border-radius: 4px; border-left: 3px solid var(--mint-green); margin: 0;">
                                                                        {{ $lead->notes }}
                                                                    </p>
                                                                </div>
                                                            @endif
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
                        </form>
                        
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
            <div class="tab-panel" id="tab-livechat">
                <div class="admin-card" style="padding: 0; overflow: hidden; display: flex; height: calc(100vh - 160px); min-height: 500px; border: 1px solid var(--admin-border); background-color: var(--admin-dark-sidebar); border-radius: var(--radius-lg);">
                    
                    <!-- Chat Sessions List (Sidebar) -->
                    <div style="width: 320px; border-right: 1px solid var(--admin-border); display: flex; flex-direction: column; background-color: var(--admin-dark-sidebar);">
                        <div style="padding: 1.25rem; border-bottom: 1px solid var(--admin-border);">
                            <h2 style="margin: 0; font-size: 1.2rem;">Live Support Chats</h2>
                            <p style="margin: 4px 0 0 0; font-size: 0.8rem; color: var(--admin-text-muted);">Real-time patient chat sessions</p>
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
                            <button type="button" id="admin-chat-delete-btn" onclick="deleteCurrentChat()" class="btn-action delete" style="display: none; align-items: center; gap: 4px; padding: 6px 12px; font-size: 0.8rem; height: auto; background-color: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; border-radius: 4px; cursor: pointer;">
                                <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i> Close & Delete Chat
                            </button>
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
            </div>

            <!-- 2. BRANDING & LOGOS TAB -->
            <div class="tab-panel" id="tab-branding">
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

                    <button type="submit" class="btn-submit">Save Branding</button>
                </form>
            </div>

            <!-- 3. HOMEPAGE SLIDER TAB -->
            <div class="tab-panel" id="tab-slider">
                <!-- Add New Slide -->
                <div class="admin-card">
                    <h2>Add New Homepage Hero Slide</h2>
                    <form action="{{ route('admin.slides.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="slide_title">Slide Title</label>
                                <input type="text" id="slide_title" name="title" class="form-control" required placeholder="Enter slide main title">
                            </div>
                            <div class="form-group">
                                <label for="slide_subtitle">Slide Subtitle</label>
                                <input type="text" id="slide_subtitle" name="subtitle" class="form-control" placeholder="Enter slide supporting subtitle">
                            </div>
                            <div class="form-group">
                                <label for="slide_btn_text">Button Text</label>
                                <input type="text" id="slide_btn_text" name="button_text" class="form-control" placeholder="Get Free Consultation">
                            </div>
                            <div class="form-group">
                                <label for="slide_btn_link">Button Link</label>
                                <input type="text" id="slide_btn_link" name="button_link" class="form-control" placeholder="#consultation">
                            </div>
                            <div class="form-group">
                                <label for="slide_image">Slide Image (Upload File)</label>
                                <input type="file" id="slide_image" name="image" class="form-control" accept="image/*">
                                <div style="margin-top: 10px;">
                                    <label for="slide_image_url">Or Paste Image URL</label>
                                    <input type="text" id="slide_image_url" name="image_url" class="form-control" placeholder="https://images.mlgfinedge.com/your-image.webp">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="slide_sort">Sort Order</label>
                                <input type="number" id="slide_sort" name="sort_order" class="form-control" value="0">
                            </div>
                        </div>
                        <button type="submit" class="btn-submit" style="margin-top: 1rem;">Add Slide</button>
                    </form>
                </div>

                <!-- Existing Slides List -->
                <div class="admin-card">
                    <h2>Current Active Slides</h2>
                    <div class="admin-table-wrap">
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
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
                                            <div class="image-preview">
                                                @if($slide->image_path)
                                                    <img src="{{ site_image($slide->image_path) }}" alt="Slide Preview">
                                                @else
                                                    <span style="font-size:0.75rem; color:var(--admin-text-muted);">Generic Card</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td><strong>{{ $slide->title }}</strong></td>
                                        <td>{{ Str::limit($slide->subtitle, 50) }}</td>
                                        <td>
                                            @if($slide->button_text)
                                                <span class="badge-webp" style="background-color: var(--accent-orange);">{{ $slide->button_text }} ({{ $slide->button_link }})</span>
                                            @else
                                                <span style="color:var(--admin-text-muted);">None</span>
                                            @endif
                                        </td>
                                        <td>{{ $slide->sort_order }}</td>
                                        <td>
                                            <div class="action-btns">
                                                <form action="{{ route('admin.slides.delete', $slide->id) }}" method="POST" onsubmit="return confirm('Delete this slide?')">
                                                    @csrf
                                                    <button type="submit" class="btn-action delete" title="Delete"><i data-lucide="trash-2"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align: center; color: var(--admin-text-muted);">No custom slides found. Showing default homepage layout as fallback.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- 4. TESTIMONIALS TAB -->
            <div class="tab-panel" id="tab-testimonials">
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

                <!-- Add New Testimonial -->
                <div class="admin-card">
                    <h2>Add New Customer Testimonial</h2>
                    <form action="{{ route('admin.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="test_name">Client Name</label>
                                <input type="text" id="test_name" name="name" class="form-control" required placeholder="Vikram Sharma">
                            </div>
                            <div class="form-group">
                                <label for="test_role">Client Subtitle/Role</label>
                                <input type="text" id="test_role" name="role" class="form-control" placeholder="Government Teacher, Jaipur">
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
                                    <input type="text" id="test_avatar_url" name="avatar_url" class="form-control" placeholder="https://images.mlgfinedge.com/avatar.webp">
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
                        <button type="submit" class="btn-submit">Add Testimonial</button>
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
            <div class="tab-panel" id="tab-styling">
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
            <div class="tab-panel" id="tab-pagecontents">
                <form action="{{ route('admin.page-contents.update') }}" method="POST">
                    @csrf
                    
                    <div class="admin-card">
                        <h2>Homepage - Hero Section</h2>
                        <div class="form-group">
                            <label>Hero Main Title</label>
                            <input type="text" name="contents[home][hero][title]" class="form-control" value="{{ $pageContents->where('page', 'home')->where('section', 'hero')->where('key', 'title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Hero Subtitle / Description</label>
                            <textarea name="contents[home][hero][subtitle]" class="form-control" rows="3">{{ $pageContents->where('page', 'home')->where('section', 'hero')->where('key', 'subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Homepage - Services Section Overview</h2>
                        <div class="form-group">
                            <label>Services Heading</label>
                            <input type="text" name="contents[home][services][title]" class="form-control" value="{{ $pageContents->where('page', 'home')->where('section', 'services')->where('key', 'title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Services Intro Text</label>
                            <textarea name="contents[home][services][subtitle]" class="form-control" rows="3">{{ $pageContents->where('page', 'home')->where('section', 'services')->where('key', 'subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Homepage - About Section</h2>
                        <div class="form-group">
                            <label>Pre-Heading / Category Tag</label>
                            <input type="text" name="contents[home][about][pre_title]" class="form-control" value="{{ $pageContents->where('page', 'home')->where('section', 'about')->where('key', 'pre_title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>About Section Heading</label>
                            <input type="text" name="contents[home][about][title]" class="form-control" value="{{ $pageContents->where('page', 'home')->where('section', 'about')->where('key', 'title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>About Paragraph 1</label>
                            <textarea name="contents[home][about][body_p1]" class="form-control" rows="3">{{ $pageContents->where('page', 'home')->where('section', 'about')->where('key', 'body_p1')->first()->value ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>About Paragraph 2</label>
                            <textarea name="contents[home][about][body_p2]" class="form-control" rows="3">{{ $pageContents->where('page', 'home')->where('section', 'about')->where('key', 'body_p2')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="admin-card">
                        <h2>Homepage - Why Choose Us Overview</h2>
                        <div class="form-group">
                            <label>Why Choose Us Heading</label>
                            <input type="text" name="contents[home][why_choose_us][title]" class="form-control" value="{{ $pageContents->where('page', 'home')->where('section', 'why_choose_us')->where('key', 'title')->first()->value ?? '' }}">
                        </div>
                        <div class="form-group">
                            <label>Why Choose Us Intro Subtitle</label>
                            <textarea name="contents[home][why_choose_us][subtitle]" class="form-control" rows="3">{{ $pageContents->where('page', 'home')->where('section', 'why_choose_us')->where('key', 'subtitle')->first()->value ?? '' }}</textarea>
                        </div>
                    </div>

                    <!-- Website Header Elements -->
                    <div class="admin-card">
                        <h2>Website Header & Navigation</h2>
                        <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                            Edit the navigation menu link labels and the header action button text.
                        </p>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Home Menu Label</label>
                                <input type="text" name="contents[header][menu][home]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'home')->first()->value ?? 'Home' }}">
                            </div>
                            <div class="form-group">
                                <label>About Us Menu Label</label>
                                <input type="text" name="contents[header][menu][about]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'about')->first()->value ?? 'About Us' }}">
                            </div>
                            <div class="form-group">
                                <label>Services Menu Label</label>
                                <input type="text" name="contents[header][menu][services]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'services')->first()->value ?? 'Services' }}">
                            </div>
                            <div class="form-group">
                                <label>Treatments Menu Label</label>
                                <input type="text" name="contents[header][menu][compare]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'compare')->first()->value ?? 'Treatments' }}">
                            </div>
                            <div class="form-group">
                                <label>Testimonials Menu Label</label>
                                <input type="text" name="contents[header][menu][testimonials]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'testimonials')->first()->value ?? 'Testimonials' }}">
                            </div>
                            <div class="form-group">
                                <label>Blog Menu Label</label>
                                <input type="text" name="contents[header][menu][blog]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'blog')->first()->value ?? 'Blog' }}">
                            </div>
                            <div class="form-group">
                                <label>Contact Us Menu Label</label>
                                <input type="text" name="contents[header][menu][contact]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'contact')->first()->value ?? 'Contact Us' }}">
                            </div>
                            <div class="form-group">
                                <label>Header Call to Action Button</label>
                                <input type="text" name="contents[header][menu][cta]" class="form-control" value="{{ $pageContents->where('page', 'header')->where('section', 'menu')->where('key', 'cta')->first()->value ?? 'Call Now' }}">
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
                                <label>Column 3 Heading (Clinic Services)</label>
                                <input type="text" name="contents[footer][headings][services]" class="form-control" value="{{ $pageContents->where('page', 'footer')->where('section', 'headings')->where('key', 'services')->first()->value ?? 'Clinic Services' }}">
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



                    <button type="submit" class="btn-submit">Save Page Content</button>
                </form>
            </div>

            <!-- 8. SERVICES MANAGER TAB -->
            <div class="tab-panel" id="tab-services">
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
                                <input type="text" id="ser_hero_category" name="hero_category" class="form-control" placeholder="RETAIL LOAN">
                            </div>
                            <div class="form-group">
                                <label for="ser_hero_title">Hero Main Title</label>
                                <input type="text" id="ser_hero_title" name="hero_title" class="form-control" required placeholder="Personal Loan at Low Rates">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_hero_subtitle">Hero Subtitle</label>
                                <textarea id="ser_hero_subtitle" name="hero_subtitle" class="form-control" rows="2" placeholder="Instant personal loans up to 40 Lakhs with minimum documentation."></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ser_rate_value">Key Rate Value (e.g. 10.49% p.a.)</label>
                                <input type="text" id="ser_rate_value" name="rate_value" class="form-control" placeholder="10.49% p.a.">
                            </div>
                            <div class="form-group">
                                <label for="ser_max_loan">Key Max Loan (e.g. Up to ₹40 Lakhs)</label>
                                <input type="text" id="ser_max_loan" name="max_loan" class="form-control" placeholder="Up to ₹40 Lakhs">
                            </div>
                            <div class="form-group">
                                <label for="ser_tenure">Key Tenure (e.g. Up to 72 Months)</label>
                                <input type="text" id="ser_tenure" name="tenure" class="form-control" placeholder="Up to 72 Months">
                            </div>
                            <div class="form-group">
                                <label for="ser_intro_title">Intro Title</label>
                                <input type="text" id="ser_intro_title" name="intro_title" class="form-control" placeholder="Why Choose Our Personal Loan?">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_intro_content">Intro Content (HTML Editor)</label>
                                <textarea id="ser_intro_content" name="intro_content" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ser_eligibility_criteria">Eligibility Criteria (one per line)</label>
                                <textarea id="ser_eligibility_criteria" name="eligibility_criteria" class="form-control" rows="4" placeholder="Minimum Income: ₹25,000 net/month&#10;Age Range: 21 to 60 Years"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ser_documents_required">Documents Required (one per line)</label>
                                <textarea id="ser_documents_required" name="documents_required" class="form-control" rows="4" placeholder="PAN Card & Aadhaar Card&#10;Last 3 Months Salary Slips"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="ser_tips_title">Tips Section Title (optional)</label>
                                <input type="text" id="ser_tips_title" name="tips_title" class="form-control" placeholder="How to get faster personal loan approval?">
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_tips_content">Tips Section Content (HTML Editor)</label>
                                <textarea id="ser_tips_content" name="tips_content" class="form-control"></textarea>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_summary">Short Summary (shown in lists & footer)</label>
                                <textarea id="ser_summary" name="summary" class="form-control" rows="2" placeholder="Brief summary of the service..."></textarea>
                            </div>
                            <div class="form-group" style="grid-column: span 2;">
                                <label for="ser_schema_markup">Custom Schema Markup (JSON-LD script)</label>
                                <textarea id="ser_schema_markup" name="schema_markup" class="form-control" rows="3" placeholder='<script type="application/ld+json">...</script>'></textarea>
                                <span style="font-size: 0.75rem; color: var(--admin-text-muted);">Paste the complete <code>&lt;script type="application/ld+json"&gt;...&lt;/script&gt;</code> block to override the default service schema.</span>
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
            <div class="tab-panel" id="tab-treatments">
                <div class="admin-card">
                    <h2 id="treatment-form-title-text">Add New Treatment Page</h2>
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
            <div class="tab-panel" id="tab-specialists">
                <div class="admin-card">
                    <h2 id="specialist-form-title-text">Add New Clinic Specialist</h2>
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
            <div class="tab-panel" id="tab-cms_pages">
                <div class="admin-card">
                    <h2 id="cms-form-title-text">Add New CMS Page</h2>
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
                                <input type="text" id="cms_metatitle" name="metatitle" class="form-control" placeholder="Disclaimer | SumitZ Clinic">
                            </div>
                            <div class="form-group">
                                <label for="cms_metakeywords">Meta Keywords (comma separated)</label>
                                <input type="text" id="cms_metakeywords" name="metakeywords" class="form-control" placeholder="disclaimer, clinic rules">
                            </div>
                        </div>

                        <div class="form-group" style="margin-top: 1rem;">
                            <label for="cms_metadescription">Meta Description (SEO)</label>
                            <textarea id="cms_metadescription" name="metadescription" class="form-control" rows="2" placeholder="Disclaimer for SumitZ Clinic services."></textarea>
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
            <div class="tab-panel" id="tab-blogs">
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
                                <input type="text" id="blog_image_url" name="image_url" class="form-control" placeholder="https://images.mlgfinedge.com/cover.webp">
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
            <div class="tab-panel" id="tab-media">
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

            <!-- WEB ANALYTICS TAB -->
            <div class="tab-panel" id="tab-analytics">
                <div class="admin-card">
                    <h2><i data-lucide="bar-chart-2" style="display: inline-block; vertical-align: middle; margin-right: 5px;"></i> Cloudflare Web Analytics</h2>
                    
                    @if(empty($site['cloudflare_api_token']) || empty($site['cloudflare_zone_id']))
                        <div style="padding: 2rem; text-align: center; background: rgba(255,255,255,0.01); border: 1px dashed var(--admin-border); border-radius: var(--radius-md);">
                            <i data-lucide="info" style="width: 48px; height: 48px; color: var(--admin-text-muted); margin-bottom: 1rem;"></i>
                            <h3 style="color: #ffffff; margin-bottom: 0.5rem;">Integration Required</h3>
                            <p style="color: var(--admin-text-muted); max-width: 500px; margin: 0 auto 1.5rem auto; font-size: 0.9rem;">
                                Please configure your Cloudflare API Token and Zone ID in the <strong>General Settings</strong> tab to view detailed web traffic metrics here.
                            </p>
                            <button onclick="switchTab('general')" class="btn-submit" style="width: auto;">Configure Settings</button>
                        </div>
                    @else
                        <div id="analytics-loader" style="padding: 3rem; text-align: center; display: flex; flex-direction: column; align-items: center; gap: 10px;">
                            <div class="ai-toast-spinner" style="width: 40px; height: 40px;"></div>
                            <span style="color: var(--admin-text-muted); font-size: 0.9rem;">Fetching data from Cloudflare...</span>
                        </div>
                        
                        <div id="analytics-error" style="display: none; padding: 2rem; background: rgba(255,107,107,0.05); border: 1px solid rgba(255,107,107,0.2); border-radius: var(--radius-md); text-align: center; margin-bottom: 1.5rem;">
                            <i data-lucide="alert-triangle" style="color: #ff6b6b; margin-bottom: 0.5rem;"></i>
                            <p id="analytics-error-msg" style="color: #ff6b6b; margin: 0; font-size: 0.9rem;"></p>
                        </div>

                        <div id="analytics-dashboard" style="display: none;">
                            <!-- Stats Cards Grid -->
                            <div class="form-grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); margin-bottom: 2rem;">
                                <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0;">
                                    <h4 style="margin: 0; font-size: 0.8rem; color: var(--admin-text-muted); text-transform: uppercase;">Total Pageviews</h4>
                                    <p id="stat-pageviews" style="font-size: 2rem; font-weight: 800; color: var(--mint-green); margin: 0.5rem 0 0 0;">0</p>
                                </div>
                                <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0;">
                                    <h4 style="margin: 0; font-size: 0.8rem; color: var(--admin-text-muted); text-transform: uppercase;">Total Requests</h4>
                                    <p id="stat-requests" style="font-size: 2rem; font-weight: 800; color: #3b82f6; margin: 0.5rem 0 0 0;">0</p>
                                </div>
                                <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0;">
                                    <h4 style="margin: 0; font-size: 0.8rem; color: var(--admin-text-muted); text-transform: uppercase;">Cache Hit Rate</h4>
                                    <p id="stat-cache" style="font-size: 2rem; font-weight: 800; color: #eab308; margin: 0.5rem 0 0 0;">0%</p>
                                </div>
                                <div class="admin-card text-center" style="padding: 1.25rem; margin-bottom: 0;">
                                    <h4 style="margin: 0; font-size: 0.8rem; color: var(--admin-text-muted); text-transform: uppercase;">Data Transferred</h4>
                                    <p id="stat-bandwidth" style="font-size: 2rem; font-weight: 800; color: #f97316; margin: 0.5rem 0 0 0;">0 MB</p>
                                </div>
                            </div>

                            <!-- Line Chart Container -->
                            <div class="admin-card" style="padding: 1.5rem; margin-bottom: 0; background: rgba(0,0,0,0.15);">
                                <h3>Traffic Metrics (Last 7 Days)</h3>
                                <div style="position: relative; height: 300px; width: 100%;">
                                    <canvas id="cloudflareChart"></canvas>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- LANDING PAGES TAB -->
            <div class="tab-panel" id="tab-landing">
                <!-- Create New Landing Page Card -->
                <div class="admin-card">
                    <h2>Create New Landing Page</h2>
                    <form action="{{ route('admin.landing.store') }}" method="POST">
                        @csrf
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="landing_title">Page Title</label>
                                <input type="text" id="landing_title" name="title" class="form-control" required placeholder="e.g. Doctors Loan Scheme" oninput="generateLandingSlug(this.value)">
                            </div>
                            <div class="form-group">
                                <label for="landing_slug">URL Slug</label>
                                <input type="text" id="landing_slug" name="slug" class="form-control" required placeholder="e.g. doctors-loan-scheme">
                            </div>
                            <div class="form-group">
                                <label for="landing_layout">Layout Structure Type</label>
                                <select id="landing_layout" name="layout_type" class="form-control" required>
                                    <option value="home">Homepage Structure (Hero, Features, About, CTA, FAQs)</option>
                                    <option value="about">About Page Structure (Hero, Story, Vision/Mission/Values, Team, CTA)</option>
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
            <div class="tab-panel" id="tab-seo">
                <div class="admin-card">
                    <h2><i data-lucide="trending-up" style="display: inline-block; vertical-align: middle; margin-right: 5px;"></i> AI SEO Recommendations & Link-Building Blueprint</h2>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                        Enter the target keywords or topics you want to rank for. Our AI will analyze search intent and draft a comprehensive blueprint of content ideas, guest articles, Quora answers, social copy, bookmarking platforms, and backlink opportunities.
                    </p>
                    <div class="form-group">
                        <label for="seo_keywords">Target Keywords (comma-separated or one per line)</label>
                        <textarea id="seo_keywords" class="form-control" rows="3" placeholder="e.g. low interest home loan, best financial adviser jaipur, secure business loans for startups"></textarea>
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
                            <button type="button" class="btn-submit" onclick="downloadSeoPdf()" style="width: auto; padding: 0.5rem 1.25rem; font-size: 0.85rem; background-color: var(--accent-teal); color: var(--primary-teal-dark); font-weight: 600; display: inline-flex; align-items: center; gap: 6px;">
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

                <!-- XML Sitemap Generator Card -->
                <div class="admin-card" style="margin-top: 2rem;">
                    <h2><i data-lucide="map" style="display: inline-block; vertical-align: middle; margin-right: 5px;"></i> XML Sitemap Generator</h2>
                    <p style="font-size: 0.85rem; color: var(--admin-text-muted); margin-bottom: 1.5rem; margin-top: -1rem;">
                        Regenerate the website XML sitemap to include all newly created service pages, blog articles, and landing pages for search engines like Google.
                    </p>
                    <form action="{{ route('admin.sitemap.generate') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-submit" style="width: auto; padding: 0.75rem 1.5rem;">
                            <i data-lucide="refresh-cw" style="width: 16px; height: 16px; display: inline-block; vertical-align: middle; margin-right: 4px;"></i> Regenerate Sitemap
                        </button>
                    </form>
                    <p style="font-size: 0.85rem; margin-top: 10px; color: var(--admin-text-muted);">
                        Sitemap URL: <a href="/sitemap.xml" target="_blank" style="color: var(--mint-green); text-decoration: underline;">/sitemap.xml</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

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
                    <textarea id="ai_landing_prompt" class="form-control" rows="4" placeholder="e.g. Special home loan advisory page for Doctors and medical professionals in Jaipur with special pre-approved limit and minimal paperwork..."></textarea>
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
                    <textarea id="ai_blog_prompt" class="form-control" rows="4" placeholder="e.g. Benefits of choosing a home loan through a broker vs directly from a bank..."></textarea>
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
                    <textarea id="ai_service_prompt" class="form-control" rows="4" placeholder="e.g. Home Renovation Loans: covering collateral requirements, processing speed, competitive interest rates starting at 8.9% p.a., and top reasons to apply."></textarea>
                </div>
                
                <div class="form-group">
                    <label for="ai_service_tone">Writing Tone</label>
                    <select id="ai_service_tone" class="form-control">
                        <option value="Professional & Financial">Professional & Financial</option>
                        <option value="Informative & Helpful">Informative & Helpful</option>
                        <option value="Persuasive & Sales-Oriented">Persuasive & Sales-Oriented</option>
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

        let cloudflareChartInstance = null;

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
            const liveUrl = liveUrlInput ? liveUrlInput.value.trim() : 'https://mlgfinedge.com';
            
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
                    const ctx = document.getElementById('cloudflareChart').getContext('2d');
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

        // Switch Tabs logic
        function switchTab(tabId) {
            const panel = document.getElementById('tab-' + tabId);
            const btn = document.getElementById('btn-' + tabId);
            const mBtn = document.getElementById('m-btn-' + tabId);
            if (!panel) return;

            document.querySelectorAll('.tab-panel').forEach(p => {
                p.classList.remove('active');
            });
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('active');
            });
            document.querySelectorAll('.mobile-tab-btn').forEach(b => {
                b.classList.remove('active');
            });
            panel.classList.add('active');
            if (btn) btn.classList.add('active');
            if (mBtn) {
                mBtn.classList.add('active');
                // Scroll the mobile tab button into view automatically
                mBtn.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }

            // Save to localStorage to persist tab across refreshes/saves
            localStorage.setItem('active_tab', tabId);

            if (tabId === 'analytics') {
                fetchCloudflareAnalytics();
            }



            // Close sidebar on mobile
            const sidebar = document.querySelector('.sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            if (sidebar && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                if (backdrop) backdrop.classList.remove('show');
            }
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

        // Initialize active tab from localStorage, defaulting to 'leads'
        window.addEventListener('DOMContentLoaded', () => {
            const activeTab = localStorage.getItem('active_tab') || 'leads';
            switchTab(activeTab);
        });

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
                <div style="border-bottom: 2px solid #5ccbb3; padding-bottom: 15px; margin-bottom: 25px;">
                    <h1 style="color: #0a1112; margin: 0 0 5px 0; font-size: 26px;">MLG Finedge - AI SEO Recommendation Blueprint</h1>
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
                filename:     'MLG_Finedge_SEO_Blueprint_' + keywords.replace(/[^a-z0-9]+/gi, '_') + '.pdf',
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
            
            const baseUrl = 'https://www.mlgfinedge.com';
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
            if (row.style.display === 'none') {
                row.style.display = 'table-row';
                setTimeout(() => lucide.createIcons(), 10);
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

        // Admin Live Chat Variables
        let currentAdminSessionId = null;
        let adminSessionsPoll = null;
        let adminMessagesPoll = null;

        // Override switchTab function to trigger chat load
        const originalSwitchTab = window.switchTab;
        window.switchTab = function(tabId) {
            if (typeof originalSwitchTab === 'function') {
                originalSwitchTab(tabId);
            } else {
                document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
                document.querySelectorAll('.tab-btn, .mobile-tab-btn').forEach(b => b.classList.remove('active'));
                
                const targetPanel = document.getElementById('tab-' + tabId);
                const targetBtn = document.getElementById('btn-' + tabId);
                const mobileBtn = document.getElementById('m-btn-' + tabId);
                
                if (targetPanel) targetPanel.classList.add('active');
                if (targetBtn) targetBtn.classList.add('active');
                if (mobileBtn) mobileBtn.classList.add('active');
                localStorage.setItem('active_tab', tabId);
            }
            
            // Manage chat pollings
            if (tabId === 'livechat') {
                loadAdminChatSessions();
                clearInterval(adminSessionsPoll);
                adminSessionsPoll = setInterval(loadAdminChatSessions, 5000);
            } else {
                clearInterval(adminSessionsPoll);
                clearInterval(adminMessagesPoll);
            }
        };

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
            
            // Update sidebar unread totals for menu badges
            let totalUnread = 0;
            sessions.forEach(s => totalUnread += s.unread_count);
            
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
                        <strong style="color: ${isSelected ? 'var(--mint-green)' : '#ffffff'}; font-size: 0.9rem; font-family: 'Outfit';">Session ${s.session_id.substr(5, 6)}</strong>
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
            loadAdminChatSessions();
            
            document.getElementById('admin-chat-input-wrap').style.display = 'flex';
            document.getElementById('admin-chat-delete-btn').style.display = 'inline-flex';
            
            document.getElementById('admin-chat-header-title').textContent = `Session ${sessionId.substr(5, 6)}`;
            document.getElementById('admin-chat-header-subtitle').textContent = `Full Session ID: ${sessionId}`;
            
            loadAdminChatMessages();
            
            clearInterval(adminMessagesPoll);
            adminMessagesPoll = setInterval(loadAdminChatMessages, 3000);
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
                if (localStorage.getItem('active_tab') === 'livechat') {
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

        // Initialize CKEditor
        let blogEditor;
        ClassicEditor
            .create(document.querySelector('#blog_content'))
            .then(editor => {
                blogEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        let serviceIntroEditor;
        let serviceTipsEditor;
        ClassicEditor
            .create(document.querySelector('#ser_intro_content'))
            .then(editor => {
                serviceIntroEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        ClassicEditor
            .create(document.querySelector('#ser_tips_content'))
            .then(editor => {
                serviceTipsEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        let treatmentEditor;
        ClassicEditor
            .create(document.querySelector('#treat_description'))
            .then(editor => {
                treatmentEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        let specialistEditor;
        ClassicEditor
            .create(document.querySelector('#spec_description'))
            .then(editor => {
                specialistEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

        let cmsEditor;
        ClassicEditor
            .create(document.querySelector('#cms_description'))
            .then(editor => {
                cmsEditor = editor;
            })
            .catch(error => {
                console.error(error);
            });

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
            document.getElementById('ser_is_active').checked = !!data.is_active;
            document.getElementById('ser_image_url').value = (data.image_path && (data.image_path.startsWith('http') || data.image_path.startsWith('//'))) ? data.image_path : '';
            document.getElementById('ser_icon_url').value = (data.icon_path && (data.icon_path.startsWith('http') || data.icon_path.startsWith('//'))) ? data.icon_path : '';
            
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
            document.getElementById('ser_schema_markup').value = '';
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
            document.getElementById('ai_landing_prompt').value = 'Create an optimized promotional landing page for ' + title + ' highlighting tailored loan interest options and fast processing payouts.';
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
                    generate_image: false
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

            fetch("{{ route('admin.ai.service') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    prompt: prompt,
                    tone: tone
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
</body>
</html>
