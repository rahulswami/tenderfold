import React, { useState } from 'react';
import { 
  BarChart3, 
  FileText, 
  Share2, 
  Users, 
  Settings, 
  Activity, 
  Sparkles, 
  Globe 
} from 'lucide-react';

import DashboardTab from './components/DashboardTab';
import BlogsTab from './components/BlogsTab';
import LinkGeneratorTab from './components/LinkGeneratorTab';
import LeadsTab from './components/LeadsTab';
import SettingsTab from './components/SettingsTab';

export default function App() {
  const [activeTab, setActiveTab] = useState('dashboard');
  const [serverUrl, setServerUrl] = useState(window.location.origin.includes('localhost') || window.location.origin.includes('127.0.0.1') ? 'http://127.0.0.1:8000' : 'https://www.drsumitz.com');

  return (
    <div className="app-viewport">
      {/* Mobile Header Bar */}
      <header className="app-header">
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
          <div style={{ width: '32px', height: '32px', borderRadius: '10px', background: 'linear-gradient(135deg, #5ccbb3, #3b82f6)', display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
            <Activity size={18} color="#0F172A" />
          </div>
          <div>
            <h1 style={{ fontSize: '1rem', fontWeight: 800, color: '#fff', lineHeight: 1.1 }}>Dr. Sumit Z</h1>
            <span style={{ fontSize: '0.68rem', color: 'var(--text-muted)' }}>Mobile Admin Suite</span>
          </div>
        </div>

        <div style={{ display: 'flex', alignItems: 'center', gap: '6px', background: 'rgba(255,255,255,0.05)', padding: '0.3rem 0.6rem', borderRadius: '20px', border: '1px solid var(--border-color)' }}>
          <div className="glow-dot" />
          <span style={{ fontSize: '0.68rem', fontWeight: 700, color: 'var(--primary-teal)' }}>
            {serverUrl.includes('127.0.0.1') ? 'Local API' : 'Live Site'}
          </span>
        </div>
      </header>

      {/* Main Tab Content Viewport */}
      <main>
        {activeTab === 'dashboard' && <DashboardTab serverUrl={serverUrl} onTabChange={setActiveTab} />}
        {activeTab === 'blogs' && <BlogsTab serverUrl={serverUrl} />}
        {activeTab === 'links' && <LinkGeneratorTab serverUrl={serverUrl} />}
        {activeTab === 'leads' && <LeadsTab serverUrl={serverUrl} />}
        {activeTab === 'settings' && <SettingsTab serverUrl={serverUrl} setServerUrl={setServerUrl} />}
      </main>

      {/* Mobile Bottom Navigation Bar */}
      <nav className="bottom-nav">
        <button 
          className={`nav-item ${activeTab === 'dashboard' ? 'active' : ''}`}
          onClick={() => setActiveTab('dashboard')}
        >
          <BarChart3 />
          <span>Dashboard</span>
        </button>

        <button 
          className={`nav-item ${activeTab === 'blogs' ? 'active' : ''}`}
          onClick={() => setActiveTab('blogs')}
        >
          <FileText />
          <span>Blogs</span>
        </button>

        <button 
          className={`nav-item ${activeTab === 'links' ? 'active' : ''}`}
          onClick={() => setActiveTab('links')}
        >
          <Share2 />
          <span>Unique Links</span>
        </button>

        <button 
          className={`nav-item ${activeTab === 'leads' ? 'active' : ''}`}
          onClick={() => setActiveTab('leads')}
        >
          <Users />
          <span>Leads</span>
        </button>

        <button 
          className={`nav-item ${activeTab === 'settings' ? 'active' : ''}`}
          onClick={() => setActiveTab('settings')}
        >
          <Settings />
          <span>Settings</span>
        </button>
      </nav>
    </div>
  );
}
