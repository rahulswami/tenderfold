import React, { useState, useEffect } from 'react';
import { FileText, Plus, Sparkles, Send, Edit, RefreshCw, CheckCircle, Image as ImageIcon } from 'lucide-react';

export default function BlogsTab({ serverUrl }) {
  const [blogs, setBlogs] = useState([]);
  const [loading, setLoading] = useState(true);
  const [showEditor, setShowEditor] = useState(false);
  const [saving, setSaving] = useState(false);

  // Form State
  const [title, setTitle] = useState('');
  const [category, setCategory] = useState('Orthopedics');
  const [summary, setSummary] = useState('');
  const [content, setContent] = useState('');
  const [aiPrompt, setAiPrompt] = useState('');
  const [aiGenerating, setAiGenerating] = useState(false);

  const fetchBlogs = async () => {
    setLoading(true);
    try {
      const res = await fetch(`${serverUrl}/api/mobile/blogs`);
      const json = await res.json();
      if (json.success) {
        setBlogs(json.blogs.data || []);
      }
    } catch (err) {
      console.error('Error fetching mobile blogs:', err);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    fetchBlogs();
  }, [serverUrl]);

  const handleSaveBlog = async (e) => {
    e.preventDefault();
    if (!title || !content) return alert('Please enter title and blog content');
    setSaving(true);
    try {
      const res = await fetch(`${serverUrl}/api/mobile/blogs/store`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          title,
          category,
          summary,
          content
        })
      });
      const json = await res.json();
      if (json.success) {
        alert('Blog published successfully!');
        setShowEditor(false);
        setTitle('');
        setContent('');
        setSummary('');
        fetchBlogs();
      }
    } catch (err) {
      alert('Error publishing blog: ' + err.message);
    } finally {
      setSaving(false);
    }
  };

  const handleAiDraft = () => {
    if (!aiPrompt) return alert('Enter a blog topic for AI generation');
    setAiGenerating(true);
    setTimeout(() => {
      setTitle(aiPrompt + ': Expert Insights by Dr. Sumit Z');
      setSummary(`A comprehensive guide explaining ${aiPrompt.toLowerCase()} with effective treatments, prevention tips, and expert medical advice.`);
      setContent(`<h2>Understanding ${aiPrompt}</h2>\n<p>${aiPrompt} is one of the most common conditions treated by orthopedic specialists. Proper diagnosis and timely medical care ensure optimum recovery.</p>\n<h3>Key Symptoms & Treatment Steps</h3>\n<ul>\n<li>Consult an experienced orthopedic doctor for accurate assessment.</li>\n<li>Follow personalized physical therapy and joint care exercises.</li>\n<li>Maintain healthy body posture and ergonomic alignment.</li>\n</ul>\n<p>Contact Dr. Sumit Z at our clinic for personalized joint treatment and recovery guidance.</p>`);
      setAiGenerating(false);
      setAiPrompt('');
    }, 1200);
  };

  return (
    <div style={{ padding: '1rem' }}>
      {/* Top Bar */}
      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '1rem' }}>
        <div>
          <h2 style={{ fontSize: '1.2rem', fontWeight: 800 }}>Mobile Blogging Studio</h2>
          <p style={{ fontSize: '0.78rem', color: 'var(--text-muted)' }}>Publish & Manage Medical Articles</p>
        </div>
        <button 
          className="mobile-btn" 
          style={{ width: 'auto', padding: '0.5rem 0.9rem', fontSize: '0.82rem' }}
          onClick={() => setShowEditor(!showEditor)}
        >
          {showEditor ? 'Cancel' : <><Plus size={16} /> New Post</>}
        </button>
      </div>

      {/* Editor Modal / Panel */}
      {showEditor && (
        <div className="mobile-card" style={{ border: '1px solid var(--primary-teal)', marginBottom: '1.5rem' }}>
          <h3 style={{ fontSize: '1rem', marginBottom: '1rem', color: 'var(--primary-teal)', display: 'flex', alignItems: 'center', gap: '6px' }}>
            <FileText size={18} /> Compose New Article
          </h3>

          {/* AI Helper Banner */}
          <div style={{ background: 'rgba(59, 130, 246, 0.1)', border: '1px solid rgba(59, 130, 246, 0.2)', padding: '0.75rem', borderRadius: '10px', marginBottom: '1rem' }}>
            <span style={{ fontSize: '0.78rem', color: '#60a5fa', fontWeight: 600, display: 'flex', alignItems: 'center', gap: '4px' }}>
              <Sparkles size={14} /> AI Writing Assistant
            </span>
            <div style={{ display: 'flex', gap: '6px', marginTop: '6px' }}>
              <input 
                type="text" 
                className="mobile-input" 
                placeholder="Topic (e.g., Post Knee Surgery Care)"
                value={aiPrompt}
                onChange={(e) => setAiPrompt(e.target.value)}
                style={{ fontSize: '0.8rem', padding: '0.4rem 0.6rem' }}
              />
              <button 
                type="button" 
                onClick={handleAiDraft}
                disabled={aiGenerating}
                style={{ background: '#3b82f6', border: 'none', color: '#fff', padding: '0 0.8rem', borderRadius: '8px', fontSize: '0.75rem', fontWeight: 700, whiteSpace: 'nowrap' }}
              >
                {aiGenerating ? 'Generating...' : 'Auto-Draft'}
              </button>
            </div>
          </div>

          <form onSubmit={handleSaveBlog} style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
            <div>
              <label style={{ fontSize: '0.75rem', color: 'var(--text-muted)', marginBottom: '4px', display: 'block' }}>Blog Title</label>
              <input 
                type="text" 
                className="mobile-input" 
                placeholder="e.g. 5 Exercises for Knee Joint Health" 
                value={title} 
                onChange={(e) => setTitle(e.target.value)} 
                required 
              />
            </div>

            <div>
              <label style={{ fontSize: '0.75rem', color: 'var(--text-muted)', marginBottom: '4px', display: 'block' }}>Category</label>
              <select 
                className="mobile-input" 
                value={category} 
                onChange={(e) => setCategory(e.target.value)}
              >
                <option value="Orthopedics" style={{ background: '#141B2D' }}>Orthopedics</option>
                <option value="Knee Replacement" style={{ background: '#141B2D' }}>Knee Replacement</option>
                <option value="Physiotherapy" style={{ background: '#141B2D' }}>Physiotherapy</option>
                <option value="Joint Pain" style={{ background: '#141B2D' }}>Joint Pain</option>
                <option value="Sports Medicine" style={{ background: '#141B2D' }}>Sports Medicine</option>
              </select>
            </div>

            <div>
              <label style={{ fontSize: '0.75rem', color: 'var(--text-muted)', marginBottom: '4px', display: 'block' }}>Short Summary</label>
              <textarea 
                className="mobile-input" 
                rows="2" 
                placeholder="Brief excerpt for list cards..."
                value={summary} 
                onChange={(e) => setSummary(e.target.value)} 
              />
            </div>

            <div>
              <label style={{ fontSize: '0.75rem', color: 'var(--text-muted)', marginBottom: '4px', display: 'block' }}>Article Content (HTML / Text)</label>
              <textarea 
                className="mobile-input" 
                rows="6" 
                placeholder="Write article content here..." 
                value={content} 
                onChange={(e) => setContent(e.target.value)} 
                required 
              />
            </div>

            <button type="submit" className="mobile-btn" disabled={saving}>
              <Send size={16} /> {saving ? 'Publishing...' : 'Publish Article'}
            </button>
          </form>
        </div>
      )}

      {/* Blogs List */}
      <div style={{ display: 'flex', flexDirection: 'column', gap: '0.75rem' }}>
        {loading ? (
          <p style={{ textAlign: 'center', color: 'var(--text-muted)', padding: '2rem' }}>Loading blog articles...</p>
        ) : blogs.length > 0 ? (
          blogs.map((b) => (
            <div key={b.id} className="mobile-card" style={{ display: 'flex', gap: '12px', alignItems: 'flex-start' }}>
              <div style={{ width: '60px', height: '60px', borderRadius: '10px', background: 'rgba(255,255,255,0.05)', display: 'flex', alignItems: 'center', justifyCenter: 'center', flexShrink: 0, overflow: 'hidden' }}>
                {b.image ? (
                  <img src={b.image.startsWith('http') ? b.image : `${serverUrl}/${b.image}`} alt={b.title} style={{ width: '100%', height: '100%', objectFit: 'cover' }} onError={(e) => { e.target.style.display = 'none'; }} />
                ) : (
                  <ImageIcon size={24} color="var(--text-subtle)" />
                )}
              </div>
              <div style={{ flex: 1, minWidth: 0 }}>
                <span className="badge badge-teal" style={{ marginBottom: '4px' }}>{b.category || 'General'}</span>
                <h4 style={{ fontSize: '0.88rem', fontWeight: 700, color: '#fff', whiteSpace: 'nowrap', overflow: 'hidden', textOverflow: 'ellipsis' }}>{b.title}</h4>
                <p style={{ fontSize: '0.72rem', color: 'var(--text-subtle)', marginTop: '2px' }}>
                  Published: {new Date(b.published_at || b.created_at).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })}
                </p>
              </div>
            </div>
          ))
        ) : (
          <div className="mobile-card text-center" style={{ padding: '2rem' }}>
            <FileText size={32} color="var(--text-subtle)" style={{ margin: '0 auto 0.5rem' }} />
            <p style={{ fontSize: '0.85rem', color: 'var(--text-muted)' }}>No blogs found. Tap 'New Post' to create your first article!</p>
          </div>
        )}
      </div>
    </div>
  );
}
