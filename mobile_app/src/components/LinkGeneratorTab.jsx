import React, { useState, useEffect } from 'react';
import { Share2, Link, Copy, Check, ExternalLink, Globe, Sparkles, Send, MessageSquare } from 'lucide-react';

export default function LinkGeneratorTab({ serverUrl }) {
  const [entities, setEntities] = useState([]);
  const [links, setLinks] = useState([]);
  const [loading, setLoading] = useState(true);

  // Generator Form State
  const [selectedEntityUrl, setSelectedEntityUrl] = useState('');
  const [platform, setPlatform] = useState('facebook');
  const [utmCampaign, setUtmCampaign] = useState('social_campaign_' + new Date().toISOString().slice(2,7).replace('-', ''));
  const [linkTitle, setLinkTitle] = useState('');
  
  const [generatedResult, setGeneratedResult] = useState(null);
  const [copied, setCopied] = useState(false);
  const [generating, setGenerating] = useState(false);

  const fetchEntitiesAndLinks = async () => {
    setLoading(true);
    try {
      const [eRes, lRes] = await Promise.all([
        fetch(`${serverUrl}/api/mobile/social/entities`),
        fetch(`${serverUrl}/api/mobile/social/links`)
      ]);
      const eJson = await eRes.json();
      const lJson = await lRes.json();

      if (eJson.success) setEntities(eJson.entities || []);
      if (lJson.success) setLinks(lJson.links?.data || []);
    } catch (err) {
      console.error('Error loading link generator data:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchEntitiesAndLinks();
  }, [serverUrl]);

  const handleGenerateLink = async (e) => {
    e.preventDefault();
    if (!selectedEntityUrl) return alert('Please select or enter a target page/blog URL');

    setGenerating(true);
    try {
      const res = await fetch(`${serverUrl}/api/mobile/social/generate-link`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          target_url: selectedEntityUrl,
          platform,
          utm_medium: 'mobile_app',
          utm_campaign: utmCampaign,
          title: linkTitle || 'Social Post Share'
        })
      });

      const json = await res.json();
      if (json.success) {
        setGeneratedResult(json);
        fetchEntitiesAndLinks();
      } else {
        alert('Error: ' + (json.message || 'Could not generate link'));
      }
    } catch (err) {
      alert('Exception generating link: ' + err.message);
    } finally {
      setGenerating(false);
    }
  };

  const copyToClipboard = (text) => {
    navigator.clipboard.writeText(text);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  const shareNative = async (url, title) => {
    if (navigator.share) {
      try {
        await navigator.share({
          title: title || 'Dr. Sumit Z Orthopedics',
          text: 'Check out this page from Dr. Sumit Z Orthopedics:',
          url: url
        });
      } catch (e) {}
    } else {
      copyToClipboard(url);
      alert('Link copied to clipboard!');
    }
  };

  const openSocialShare = (platform, url, title) => {
    let shareUrl = '';
    const encodedUrl = encodeURIComponent(url);
    const encodedText = encodeURIComponent(`Check out: ${title} - Dr. Sumit Z Orthopedic Clinic`);

    switch (platform) {
      case 'whatsapp':
        shareUrl = `https://api.whatsapp.com/send?text=${encodedText}%20${encodedUrl}`;
        break;
      case 'facebook':
        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
        break;
      case 'linkedin':
        shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodedUrl}`;
        break;
      case 'twitter':
      case 'x':
        shareUrl = `https://twitter.com/intent/tweet?url=${encodedUrl}&text=${encodedText}`;
        break;
      default:
        shareNative(url, title);
        return;
    }
    window.open(shareUrl, '_blank');
  };

  return (
    <div style={{ padding: '1rem' }}>
      {/* Top Title */}
      <div style={{ marginBottom: '1rem' }}>
        <h2 style={{ fontSize: '1.2rem', fontWeight: 800 }}>Unique Social Link Studio</h2>
        <p style={{ fontSize: '0.78rem', color: 'var(--text-muted)' }}>Generate Tracked Short URLs & Share to Social Apps</p>
      </div>

      {/* Generator Card */}
      <div className="mobile-card" style={{ border: '1px solid var(--border-accent)' }}>
        <h3 style={{ fontSize: '0.95rem', marginBottom: '0.85rem', color: 'var(--primary-teal)', display: 'flex', alignItems: 'center', gap: '6px' }}>
          <Sparkles size={16} /> Create Unique Social Link
        </h3>

        <form onSubmit={handleGenerateLink} style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
          {/* Target Page Selector */}
          <div>
            <label style={{ fontSize: '0.75rem', color: 'var(--text-muted)', marginBottom: '4px', display: 'block' }}>Select Target Page / Blog Post</label>
            <select 
              className="mobile-input" 
              value={selectedEntityUrl}
              onChange={(e) => {
                setSelectedEntityUrl(e.target.value);
                const opt = e.target.options[e.target.selectedIndex];
                if (opt) setLinkTitle(opt.text);
              }}
              required
            >
              <option value="" style={{ background: '#141B2D' }}>-- Choose Page or Blog --</option>
              {entities.map((ent, i) => (
                <option key={i} value={ent.url} style={{ background: '#141B2D' }}>
                  {ent.subtitle} - {ent.title}
                </option>
              ))}
            </select>
          </div>

          {/* Or Custom URL Input */}
          <div>
            <input 
              type="url" 
              className="mobile-input" 
              placeholder="Or enter custom URL (e.g. https://www.drsumitz.com/...)"
              value={selectedEntityUrl}
              onChange={(e) => setSelectedEntityUrl(e.target.value)}
            />
          </div>

          {/* Platform Selector Grid */}
          <div>
            <label style={{ fontSize: '0.75rem', color: 'var(--text-muted)', marginBottom: '6px', display: 'block' }}>Target Social Media Platform</label>
            <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '6px' }}>
              {[
                { id: 'facebook', label: 'Facebook', icon: 'FB' },
                { id: 'instagram', label: 'Instagram', icon: 'IG' },
                { id: 'whatsapp', label: 'WhatsApp', icon: 'WA' },
                { id: 'linkedin', label: 'LinkedIn', icon: 'IN' },
                { id: 'twitter', label: 'Twitter / X', icon: 'X' },
                { id: 'practo', label: 'Practo', icon: 'PR' },
              ].map((p) => (
                <button
                  type="button"
                  key={p.id}
                  onClick={() => setPlatform(p.id)}
                  style={{
                    background: platform === p.id ? 'rgba(92, 203, 179, 0.2)' : 'rgba(255,255,255,0.04)',
                    border: platform === p.id ? '1px solid var(--primary-teal)' : '1px solid var(--border-color)',
                    color: platform === p.id ? 'var(--primary-teal)' : 'var(--text-muted)',
                    padding: '0.5rem',
                    borderRadius: '8px',
                    fontSize: '0.75rem',
                    fontWeight: 600,
                    cursor: 'pointer'
                  }}
                >
                  {p.label}
                </button>
              ))}
            </div>
          </div>

          <button type="submit" className="mobile-btn" disabled={generating}>
            <Link size={16} /> {generating ? 'Generating Link...' : 'Generate Tracked Short URL'}
          </button>
        </form>
      </div>

      {/* Generated Result Banner */}
      {generatedResult && (
        <div className="mobile-card" style={{ border: '1px solid var(--primary-teal)', background: 'rgba(92, 203, 179, 0.08)' }}>
          <span style={{ fontSize: '0.72rem', color: 'var(--primary-teal)', fontWeight: 700, textTransform: 'uppercase' }}>Unique Tracked URL Ready!</span>
          <p style={{ fontSize: '0.95rem', fontWeight: 800, color: '#fff', margin: '4px 0 8px', wordBreak: 'break-all' }}>
            {generatedResult.short_url}
          </p>

          <div style={{ display: 'flex', gap: '8px', flexWrap: 'wrap', marginTop: '0.5rem' }}>
            <button 
              className="mobile-btn" 
              style={{ width: 'auto', flex: 1, padding: '0.5rem 0.8rem', fontSize: '0.8rem' }}
              onClick={() => copyToClipboard(generatedResult.short_url)}
            >
              {copied ? <Check size={14} /> : <Copy size={14} />} {copied ? 'Copied!' : 'Copy Link'}
            </button>
            <button 
              className="mobile-btn mobile-btn-outline" 
              style={{ width: 'auto', flex: 1, padding: '0.5rem 0.8rem', fontSize: '0.8rem' }}
              onClick={() => openSocialShare(platform, generatedResult.short_url, linkTitle)}
            >
              <Send size={14} /> Share Now
            </button>
          </div>
        </div>
      )}

      {/* Previous Tracked Social Links */}
      <h3 style={{ fontSize: '0.95rem', marginBottom: '0.75rem', marginTop: '1.25rem' }}>Generated Social Links & Clicks</h3>
      <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
        {loading ? (
          <p style={{ textAlign: 'center', color: 'var(--text-muted)' }}>Loading tracked links...</p>
        ) : links.length > 0 ? (
          links.map((l) => (
            <div key={l.id} className="mobile-card" style={{ padding: '0.85rem 1rem' }}>
              <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '4px' }}>
                <span className="badge badge-teal" style={{ textTransform: 'capitalize' }}>{l.utm_source || 'Social'}</span>
                <span style={{ fontSize: '0.75rem', fontWeight: 800, color: 'var(--primary-teal)' }}>
                  {l.clicks || 0} Clicks
                </span>
              </div>
              <p style={{ fontSize: '0.82rem', fontWeight: 700, color: '#fff', marginBottom: '2px' }}>{l.title || 'Social Post Link'}</p>
              <p style={{ fontSize: '0.75rem', color: 'var(--text-subtle)', wordBreak: 'break-all' }}>{serverUrl}/s/{l.code}</p>

              <div style={{ display: 'flex', gap: '8px', marginTop: '8px' }}>
                <button 
                  onClick={() => copyToClipboard(`${serverUrl}/s/${l.code}`)}
                  style={{ background: 'rgba(255,255,255,0.05)', border: '1px solid var(--border-color)', color: '#fff', padding: '0.35rem 0.6rem', borderRadius: '6px', fontSize: '0.72rem', display: 'flex', alignItems: 'center', gap: '4px', cursor: 'pointer' }}
                >
                  <Copy size={12} /> Copy
                </button>
                <button 
                  onClick={() => openSocialShare(l.utm_source, `${serverUrl}/s/${l.code}`, l.title)}
                  style={{ background: 'rgba(59, 130, 246, 0.15)', border: 'none', color: '#3b82f6', padding: '0.35rem 0.6rem', borderRadius: '6px', fontSize: '0.72rem', display: 'flex', alignItems: 'center', gap: '4px', cursor: 'pointer' }}
                >
                  <Send size={12} /> Share
                </button>
              </div>
            </div>
          ))
        ) : (
          <p style={{ fontSize: '0.8rem', color: 'var(--text-muted)', textAlign: 'center' }}>No tracked links generated yet.</p>
        )}
      </div>
    </div>
  );
}
