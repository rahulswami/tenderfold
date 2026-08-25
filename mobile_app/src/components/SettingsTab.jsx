import React from 'react';
import { Settings, Server, Globe, CheckCircle, Shield } from 'lucide-react';

export default function SettingsTab({ serverUrl, setServerUrl }) {
  return (
    <div style={{ padding: '1rem' }}>
      <div style={{ marginBottom: '1rem' }}>
        <h2 style={{ fontSize: '1.2rem', fontWeight: 800 }}>Mobile App Settings</h2>
        <p style={{ fontSize: '0.78rem', color: 'var(--text-muted)' }}>Configure Backend Host & App Preferences</p>
      </div>

      <div className="mobile-card">
        <h3 style={{ fontSize: '0.95rem', marginBottom: '0.85rem', color: 'var(--primary-teal)', display: 'flex', alignItems: 'center', gap: '6px' }}>
          <Server size={18} /> Backend Server Connection
        </h3>

        <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
          <label style={{ fontSize: '0.75rem', color: 'var(--text-muted)' }}>Target Backend API Host</label>
          
          <div 
            onClick={() => setServerUrl('https://www.drsumitz.com')}
            style={{
              padding: '0.75rem',
              borderRadius: '10px',
              border: serverUrl === 'https://www.drsumitz.com' ? '1px solid var(--primary-teal)' : '1px solid var(--border-color)',
              background: serverUrl === 'https://www.drsumitz.com' ? 'rgba(92, 203, 179, 0.1)' : 'rgba(255,255,255,0.03)',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between'
            }}
          >
            <div>
              <p style={{ fontSize: '0.85rem', fontWeight: 700, color: '#fff' }}>Production Server</p>
              <p style={{ fontSize: '0.72rem', color: 'var(--text-subtle)' }}>https://www.drsumitz.com</p>
            </div>
            {serverUrl === 'https://www.drsumitz.com' && <CheckCircle size={18} color="var(--primary-teal)" />}
          </div>

          <div 
            onClick={() => setServerUrl('http://127.0.0.1:8000')}
            style={{
              padding: '0.75rem',
              borderRadius: '10px',
              border: serverUrl === 'http://127.0.0.1:8000' ? '1px solid var(--primary-teal)' : '1px solid var(--border-color)',
              background: serverUrl === 'http://127.0.0.1:8000' ? 'rgba(92, 203, 179, 0.1)' : 'rgba(255,255,255,0.03)',
              cursor: 'pointer',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'space-between'
            }}
          >
            <div>
              <p style={{ fontSize: '0.85rem', fontWeight: 700, color: '#fff' }}>Local Server (Artisan)</p>
              <p style={{ fontSize: '0.72rem', color: 'var(--text-subtle)' }}>http://127.0.0.1:8000</p>
            </div>
            {serverUrl === 'http://127.0.0.1:8000' && <CheckCircle size={18} color="var(--primary-teal)" />}
          </div>

          <div style={{ marginTop: '0.5rem' }}>
            <label style={{ fontSize: '0.75rem', color: 'var(--text-muted)', marginBottom: '4px', display: 'block' }}>Or Enter Custom Host URL</label>
            <input 
              type="url" 
              className="mobile-input" 
              value={serverUrl} 
              onChange={(e) => setServerUrl(e.target.value)} 
              placeholder="e.g. https://yourdomain.com" 
            />
          </div>
        </div>
      </div>

      <div className="mobile-card" style={{ background: 'rgba(59, 130, 246, 0.08)', border: '1px solid rgba(59, 130, 246, 0.2)' }}>
        <h4 style={{ fontSize: '0.85rem', color: '#60a5fa', marginBottom: '4px', display: 'flex', alignItems: 'center', gap: '6px' }}>
          <Shield size={16} /> Hybrid Mobile Web App
        </h4>
        <p style={{ fontSize: '0.78rem', color: 'var(--text-muted)', lineHeight: '1.4' }}>
          This hybrid React app can be accessed on any mobile browser or packaged with Capacitor/Cordova into an APK for Android & iOS!
        </p>
      </div>
    </div>
  );
}
