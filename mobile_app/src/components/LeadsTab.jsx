import React, { useState, useEffect } from 'react';
import { Users, Phone, MessageSquare, Mail, Calendar, CheckCircle, Clock } from 'lucide-react';

export default function LeadsTab({ serverUrl }) {
  const [leads, setLeads] = useState([]);
  const [loading, setLoading] = useState(true);
  const [statusFilter, setStatusFilter] = useState('all');

  const fetchLeads = async (filter = statusFilter) => {
    setLoading(true);
    try {
      const res = await fetch(`${serverUrl}/api/mobile/leads?status=${filter}`);
      const json = await res.json();
      if (json.success) {
        setLeads(json.leads?.data || []);
      }
    } catch (err) {
      console.error('Error fetching mobile leads:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchLeads(statusFilter);
  }, [statusFilter, serverUrl]);

  const updateStatus = async (id, newStatus) => {
    try {
      const res = await fetch(`${serverUrl}/api/mobile/leads/${id}/status`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ status: newStatus })
      });
      const json = await res.json();
      if (json.success) {
        fetchLeads(statusFilter);
      }
    } catch (err) {
      alert('Error updating status: ' + err.message);
    }
  };

  return (
    <div style={{ padding: '1rem' }}>
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '1rem' }}>
        <div>
          <h2 style={{ fontSize: '1.2rem', fontWeight: 800 }}>Patient Inquiries</h2>
          <p style={{ fontSize: '0.78rem', color: 'var(--text-muted)' }}>Real-Time Leads & Appointment Requests</p>
        </div>
        <select 
          className="mobile-input" 
          style={{ width: 'auto', padding: '0.35rem 0.6rem', fontSize: '0.78rem' }}
          value={statusFilter}
          onChange={(e) => setStatusFilter(e.target.value)}
        >
          <option value="all" style={{ background: '#141B2D' }}>All Leads</option>
          <option value="new" style={{ background: '#141B2D' }}>New Inquiries</option>
          <option value="contacted" style={{ background: '#141B2D' }}>Contacted</option>
        </select>
      </div>

      <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
        {loading ? (
          <p style={{ textAlign: 'center', color: 'var(--text-muted)', padding: '2rem' }}>Fetching patient leads...</p>
        ) : leads.length > 0 ? (
          leads.map((l) => (
            <div key={l.id} className="mobile-card">
              <div style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'flex-start', marginBottom: '6px' }}>
                <h4 style={{ fontSize: '0.95rem', fontWeight: 700, color: '#fff' }}>{l.name}</h4>
                <span className={`badge ${l.status === 'new' ? 'badge-rose' : 'badge-teal'}`}>
                  {l.status || 'new'}
                </span>
              </div>

              {l.treatment_name && (
                <p style={{ fontSize: '0.78rem', color: 'var(--primary-teal)', fontWeight: 600, marginBottom: '6px' }}>
                  Interested in: {l.treatment_name}
                </p>
              )}

              <p style={{ fontSize: '0.8rem', color: 'var(--text-muted)', marginBottom: '8px', lineHeight: '1.4' }}>
                {l.message || 'Requested consultation / call back'}
              </p>

              <div style={{ fontSize: '0.72rem', color: 'var(--text-subtle)', display: 'flex', alignItems: 'center', gap: '4px', marginBottom: '10px' }}>
                <Clock size={12} /> Received: {new Date(l.created_at).toLocaleString()}
              </div>

              {/* Action Buttons: Phone & WhatsApp */}
              <div style={{ display: 'flex', gap: '8px', borderTop: '1px solid rgba(255,255,255,0.05)', paddingTop: '8px' }}>
                {l.phone && (
                  <>
                    <a 
                      href={`tel:${l.phone}`} 
                      style={{ flex: 1, background: 'rgba(92, 203, 179, 0.15)', color: 'var(--primary-teal)', borderRadius: '8px', padding: '0.4rem', textDecoration: 'none', fontSize: '0.75rem', fontWeight: 700, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '4px' }}
                      onClick={() => updateStatus(l.id, 'contacted')}
                    >
                      <Phone size={12} /> Call ({l.phone})
                    </a>
                    <a 
                      href={`https://api.whatsapp.com/send?phone=${l.phone.replace(/[^0-9]/g, '')}&text=Hello%20${encodeURIComponent(l.name)},%20this%20is%20Dr.%20Sumit%20Z%20Orthopedic%20Clinic.`}
                      target="_blank"
                      rel="noreferrer"
                      style={{ flex: 1, background: 'rgba(34, 197, 94, 0.15)', color: '#22c55e', borderRadius: '8px', padding: '0.4rem', textDecoration: 'none', fontSize: '0.75rem', fontWeight: 700, display: 'flex', alignItems: 'center', justifyContent: 'center', gap: '4px' }}
                      onClick={() => updateStatus(l.id, 'contacted')}
                    >
                      <MessageSquare size={12} /> WhatsApp
                    </a>
                  </>
                )}
              </div>
            </div>
          ))
        ) : (
          <div className="mobile-card text-center" style={{ padding: '2rem' }}>
            <Users size={32} color="var(--text-subtle)" style={{ margin: '0 auto 0.5rem' }} />
            <p style={{ fontSize: '0.85rem', color: 'var(--text-muted)' }}>No patient leads found.</p>
          </div>
        )}
      </div>
    </div>
  );
}
