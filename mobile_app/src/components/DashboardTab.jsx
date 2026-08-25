import React, { useState, useEffect } from 'react';
import { 
  BarChart3, 
  TrendingUp, 
  Users, 
  PhoneCall, 
  MousePointer, 
  Search, 
  Globe, 
  RefreshCw, 
  MapPin, 
  Sparkles, 
  Share2,
  FileText
} from 'lucide-react';
import { Line, Bar } from 'react-chartjs-2';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from 'chart.js';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  BarElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

export default function DashboardTab({ serverUrl, onTabChange }) {
  const [data, setData] = useState(null);
  const [loading, setLoading] = useState(true);
  const [range, setRange] = useState('last_7_days');

  const fetchDashboardData = async (selectedRange = range) => {
    setLoading(true);
    try {
      const res = await fetch(`${serverUrl}/api/mobile/dashboard?date_range=${selectedRange}`);
      const json = await res.json();
      if (json.success) {
        setData(json);
      }
    } catch (err) {
      console.error('Error fetching mobile dashboard data:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchDashboardData(range);
  }, [range, serverUrl]);

  const ga = data?.analytics?.analytics || {};
  const gsc = data?.analytics?.search_console || {};
  const gmb = data?.analytics?.my_business || {};
  const counts = data?.counts || {};

  // GA4 Chart Data
  const gaChartData = {
    labels: ga.daily ? ga.daily.map(d => d.date) : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
    datasets: [
      {
        label: 'Sessions',
        data: ga.daily ? ga.daily.map(d => d.sessions) : [120, 150, 180, 210, 190, 240, 260],
        borderColor: '#5ccbb3',
        backgroundColor: 'rgba(92, 203, 179, 0.15)',
        fill: true,
        tension: 0.35,
        pointRadius: 4,
      },
      {
        label: 'Active Users',
        data: ga.daily ? ga.daily.map(d => d.users) : [90, 110, 140, 160, 150, 180, 200],
        borderColor: '#3b82f6',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        fill: true,
        tension: 0.35,
        pointRadius: 4,
      }
    ]
  };

  const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        position: 'top',
        labels: { color: '#94A3B8', font: { family: 'Plus Jakarta Sans', size: 11 } }
      }
    },
    scales: {
      x: { ticks: { color: '#64748B' }, grid: { color: 'rgba(255,255,255,0.05)' } },
      y: { ticks: { color: '#64748B' }, grid: { color: 'rgba(255,255,255,0.05)' } }
    }
  };

  return (
    <div style={{ padding: '1rem' }}>
      {/* Date Switcher & Refresh */}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '1rem' }}>
        <div>
          <h2 style={{ fontSize: '1.2rem', fontWeight: 800 }}>Reporting Hub</h2>
          <p style={{ fontSize: '0.78rem', color: 'var(--text-muted)' }}>GA4, Search Console & Local GMB Insights</p>
        </div>
        <div style={{ display: 'flex', gap: '8px' }}>
          <select 
            value={range} 
            onChange={(e) => setRange(e.target.value)}
            style={{
              background: 'rgba(255,255,255,0.06)',
              border: '1px solid var(--border-color)',
              color: '#fff',
              padding: '0.35rem 0.6rem',
              borderRadius: '8px',
              fontSize: '0.78rem'
            }}
          >
            <option value="last_7_days" style={{ background: '#141B2D' }}>7 Days</option>
            <option value="last_30_days" style={{ background: '#141B2D' }}>30 Days</option>
          </select>
          <button 
            onClick={() => fetchDashboardData(range)}
            style={{
              background: 'rgba(92, 203, 179, 0.15)',
              border: 'none',
              color: 'var(--primary-teal)',
              padding: '0.4rem',
              borderRadius: '8px',
              cursor: 'pointer'
            }}
          >
            <RefreshCw size={16} className={loading ? 'animate-spin' : ''} />
          </button>
        </div>
      </div>

      {/* Quick Action Bar */}
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.75rem', marginBottom: '1rem' }}>
        <button className="mobile-btn" onClick={() => onTabChange('blogs')}>
          <FileText size={16} /> Write Blog
        </button>
        <button className="mobile-btn mobile-btn-outline" onClick={() => onTabChange('links')}>
          <Share2 size={16} /> Unique Social Link
        </button>
      </div>

      {/* Primary KPI Grid */}
      <div style={{ display: 'grid', gridTemplateColumns: '1fr 1fr', gap: '0.75rem', marginBottom: '1rem' }}>
        <div className="mobile-card" style={{ padding: '1rem', borderTop: '3px solid var(--primary-teal)' }}>
          <span style={{ fontSize: '0.72rem', color: 'var(--text-muted)', textTransform: 'uppercase' }}>GA4 Users</span>
          <h3 style={{ fontSize: '1.5rem', fontWeight: 800, color: '#fff', marginTop: '4px' }}>
            {(ga.unique_users || 0).toLocaleString()}
          </h3>
          <span className="badge badge-teal" style={{ marginTop: '6px' }}>
            <Globe size={10} /> {ga.sessions || 0} Sessions
          </span>
        </div>

        <div className="mobile-card" style={{ padding: '1rem', borderTop: '3px solid var(--accent-blue)' }}>
          <span style={{ fontSize: '0.72rem', color: 'var(--text-muted)', textTransform: 'uppercase' }}>GSC Search Clicks</span>
          <h3 style={{ fontSize: '1.5rem', fontWeight: 800, color: '#3b82f6', marginTop: '4px' }}>
            {(gsc.clicks || 0).toLocaleString()}
          </h3>
          <span className="badge badge-blue" style={{ marginTop: '6px' }}>
            <Search size={10} /> {gsc.impressions || 0} Imps
          </span>
        </div>

        <div className="mobile-card" style={{ padding: '1rem', borderTop: '3px solid var(--accent-purple)' }}>
          <span style={{ fontSize: '0.72rem', color: 'var(--text-muted)', textTransform: 'uppercase' }}>GMB Profile Calls</span>
          <h3 style={{ fontSize: '1.5rem', fontWeight: 800, color: '#8b5cf6', marginTop: '4px' }}>
            {(gmb.calls || 0).toLocaleString()}
          </h3>
          <span className="badge badge-purple" style={{ marginTop: '6px' }}>
            <PhoneCall size={10} /> {gmb.visitors || 0} Views
          </span>
        </div>

        <div className="mobile-card" style={{ padding: '1rem', borderTop: '3px solid var(--accent-amber)' }}>
          <span style={{ fontSize: '0.72rem', color: 'var(--text-muted)', textTransform: 'uppercase' }}>Patient Leads</span>
          <h3 style={{ fontSize: '1.5rem', fontWeight: 800, color: '#f59e0b', marginTop: '4px' }}>
            {counts.leads || 0}
          </h3>
          <span className="badge badge-amber" style={{ marginTop: '6px' }}>
            <Users size={10} /> {counts.new_leads || 0} New
          </span>
        </div>
      </div>

      {/* Traffic Timeline Chart */}
      <div className="mobile-card">
        <h4 style={{ fontSize: '0.9rem', marginBottom: '0.75rem', display: 'flex', alignItems: 'center', gap: '6px' }}>
          <TrendingUp size={16} color="var(--primary-teal)" /> Website Traffic Trend
        </h4>
        <div style={{ height: '180px' }}>
          <Line data={gaChartData} options={chartOptions} />
        </div>
      </div>

      {/* Organic Keywords */}
      <div className="mobile-card">
        <h4 style={{ fontSize: '0.9rem', marginBottom: '0.75rem', display: 'flex', alignItems: 'center', gap: '6px' }}>
          <Search size={16} color="#3b82f6" /> Top Google Organic Queries
        </h4>
        <div style={{ display: 'flex', flexDirection: 'column', gap: '8px' }}>
          {gsc.keywords && gsc.keywords.length > 0 ? (
            gsc.keywords.slice(0, 5).map((kw, i) => (
              <div key={i} style={{ display: 'flex', alignItems: 'center', justifyBetween: 'space-between', padding: '0.5rem 0', borderBottom: '1px solid rgba(255,255,255,0.05)' }}>
                <div style={{ flex: 1, paddingRight: '8px' }}>
                  <p style={{ fontSize: '0.82rem', fontWeight: 600 }}>{kw.keyword}</p>
                  <span style={{ fontSize: '0.7rem', color: 'var(--text-subtle)' }}>Rank #{kw.position}</span>
                </div>
                <div style={{ textAlign: 'right' }}>
                  <span style={{ fontSize: '0.82rem', fontWeight: 700, color: 'var(--primary-teal)' }}>{kw.clicks} clicks</span>
                  <p style={{ fontSize: '0.7rem', color: 'var(--text-subtle)' }}>{kw.ctr}% CTR</p>
                </div>
              </div>
            ))
          ) : (
            <p style={{ fontSize: '0.8rem', color: 'var(--text-muted)', textAlign: 'center' }}>Connecting to Search Console API...</p>
          )}
        </div>
      </div>
    </div>
  );
}
