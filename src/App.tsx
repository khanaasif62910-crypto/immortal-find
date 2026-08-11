import React, { useState, useEffect } from 'react';
import { QRCodeSVG } from 'qrcode.react';
import portalLogo from './assets/images/immortal_find_logo_1785166643443.jpg';
import redHeroBg from './assets/images/red_energy_hero_bg_1785675327243.jpg';
import nebulaBg from './assets/images/red_nebula_bg_1786028589974.jpg';
import {
  Search, ShieldAlert, HeartHandshake, ShieldCheck, Tag, PlusCircle,
  FileText, User, Phone, MapPin, Calendar, Award, CheckCircle, XCircle,
  Trash2, LogIn, LogOut, ArrowRight, RefreshCw, LayoutDashboard, Filter,
  Eye, Check, FolderOpen, Key, AlertTriangle, Download, Info, Mail, Laptop,
  Upload, Image as ImageIcon, Camera, X, Compass, Map, Navigation, Globe,
  Layers, ZoomIn, ZoomOut, Crosshair, QrCode, Printer, Share2, Copy, ExternalLink, Sparkles
} from 'lucide-react';

// --- TYPES ---
type Role = 'guest' | 'user' | 'admin';

interface Category {
  id: number;
  category_name: string;
}

interface Item {
  id: number;
  type: 'lost' | 'found';
  user_name: string;
  category_id: number;
  item_name: string;
  description: string;
  image: string;
  location: string;
  date: string;
  reward?: number;
  contact: string;
  status: 'Pending' | 'Approved' | 'Rejected' | 'Claimed';
  created_at: string;
}

// --- TAMIL NADU LOCATION DATA ---
const TAMILNADU_DISTRICTS = [
  'All Tamil Nadu',
  'Cheyyar',
  'Tiruvannamalai',
  'Chennai',
  'Coimbatore',
  'Madurai',
  'Tiruchirappalli',
  'Salem',
  'Tirunelveli',
  'Vellore',
  'Erode',
  'Thanjavur',
  'Kanchipuram',
  'Chengalpattu',
  'Tiruppur',
  'Dindigul',
  'Cuddalore',
  'Kanyakumari',
  'Karur',
  'Nagapattinam',
  'Ramanathapuram',
  'Virudhunagar',
  'Tuticorin (Thoothukudi)',
  'Dharmapuri',
  'Krishnagiri',
  'Namakkal',
  'Theni',
  'Nilgiris (Ooty)',
  'Pudukkottai',
  'Sivaganga',
  'Perambalur',
  'Ariyalur',
  'Villupuram',
  'Kallakurichi',
  'Tirupathur',
  'Ranipet',
  'Tenkasi',
  'Mayiladuthurai',
  'Tiruvallur'
];

interface TNLocationHotspot {
  id: string;
  name: string;
  district: string;
  category: 'University / College' | 'Transport Hub' | 'Public Landmark' | 'Tech Park';
  coords: { lat: number; lng: number };
  popularSpots: string[];
}

const TAMILNADU_HOTSPOTS: TNLocationHotspot[] = [
  {
    id: 'cheyyar-arts-college',
    name: 'Arignar Anna Govt Arts College Cheyyar',
    district: 'Cheyyar',
    category: 'University / College',
    coords: { lat: 12.6628, lng: 79.5412 },
    popularSpots: ['BCA Computer Science Lab', 'Central Library Reading Hall', 'Main Auditorium Quadrangle', 'College Canteen', 'Main Entrance Gate']
  },
  {
    id: 'cheyyar-indo-american',
    name: 'Indo-American College Cheyyar',
    district: 'Cheyyar',
    category: 'University / College',
    coords: { lat: 12.6515, lng: 79.5290 },
    popularSpots: ['Main Academic Block', 'Computer Science & BCA Lab', 'College Library & Reading Room', 'Student Seminar Hall', 'College Sports Ground & Canteen']
  },
  {
    id: 'cheyyar-bus-stand',
    name: 'Cheyyar Bus Stand & Market Square',
    district: 'Cheyyar',
    category: 'Transport Hub',
    coords: { lat: 12.6580, lng: 79.5440 },
    popularSpots: ['Town Bus Platform', 'Mofussil Bus Bay 3', 'Clock Tower Square', 'Auto Stand Circle']
  },
  {
    id: 'cheyyar-sipcot',
    name: 'Cheyyar SIPCOT Industrial Park',
    district: 'Cheyyar',
    category: 'Tech Park',
    coords: { lat: 12.6850, lng: 79.5600 },
    popularSpots: ['Main Gate Security Check', 'Phase 1 Admin Building', 'Lotus Footwear Entrance', 'Industrial Staff Canteen']
  },
  {
    id: 'cheyyar-anish',
    name: 'Cheyyar Polytechnic & Anish College',
    district: 'Cheyyar',
    category: 'University / College',
    coords: { lat: 12.6600, lng: 79.5350 },
    popularSpots: ['Polytechnic Workshop Block', 'Student Canteen', 'Central Sports Ground']
  },
  {
    id: 'tvm-temple',
    name: 'Arulmigu Annamalaiyar Temple Thiruvannamalai',
    district: 'Tiruvannamalai',
    category: 'Public Landmark',
    coords: { lat: 12.2319, lng: 79.0677 },
    popularSpots: ['Raja Gopuram Square', 'Girivalam Path Entrance', 'Thousand Pillar Hall', 'Temple Office']
  },
  {
    id: 'chennai-anna',
    name: 'Anna University Guindy',
    district: 'Chennai',
    category: 'University / College',
    coords: { lat: 13.0102, lng: 80.2354 },
    popularSpots: ['Central Library Reading Room', 'CEG Main Building', 'Alagappa Tech Canteen', 'Red Building Plaza', 'Hostel Block 5']
  },
  {
    id: 'chennai-iitm',
    name: 'IIT Madras Adyar',
    district: 'Chennai',
    category: 'University / College',
    coords: { lat: 12.9915, lng: 80.2337 },
    popularSpots: ['Gajendra Circle', 'Central Library', 'Himalaya Canteen', 'OAT Open Air Theatre', 'BSB Block']
  },
  {
    id: 'chennai-loyola',
    name: 'Loyola College Nungambakkam',
    district: 'Chennai',
    category: 'University / College',
    coords: { lat: 13.0623, lng: 80.2338 },
    popularSpots: ['Main Building Lawn', 'Jubilee Quadrangle', 'College Canteen', 'Central Library']
  },
  {
    id: 'trichy-nit',
    name: 'NIT Tiruchirappalli (NIT Trichy)',
    district: 'Tiruchirappalli',
    category: 'University / College',
    coords: { lat: 10.7612, lng: 78.8139 },
    popularSpots: ['Octagon Computer Center', 'Central Library', 'Mega Mess Canteen', 'Orion Building', 'Admin Block Ground']
  },
  {
    id: 'coimbatore-psg',
    name: 'PSG College of Technology',
    district: 'Coimbatore',
    category: 'University / College',
    coords: { lat: 11.0247, lng: 76.9932 },
    popularSpots: ['GRD Quadrangle', 'F Block Computer Lab', 'Main Canteen', 'Central Library']
  },
  {
    id: 'vellore-vit',
    name: 'VIT University Vellore',
    district: 'Vellore',
    category: 'University / College',
    coords: { lat: 12.9692, lng: 79.1559 },
    popularSpots: ['Technology Tower (TT)', 'Food Park Canteen', 'Anna Auditorium', 'SJT Building']
  },
  {
    id: 'chengalpattu-srm',
    name: 'SRM Institute Kattankulathur',
    district: 'Chengalpattu',
    category: 'University / College',
    coords: { lat: 12.8231, lng: 80.0442 },
    popularSpots: ['Tech Park Building', 'Java Canteen', 'University Arch Entrance', 'Central Library']
  },
  {
    id: 'thanjavur-sastra',
    name: 'SASTRA Deemed University',
    district: 'Thanjavur',
    category: 'University / College',
    coords: { lat: 10.7288, lng: 79.0201 },
    popularSpots: ['Nirman Vihar', 'Chanakya Auditorium', 'Canara Bank Square', 'Central Library']
  },
  {
    id: 'madurai-tce',
    name: 'Thiagarajar College of Engineering',
    district: 'Madurai',
    category: 'University / College',
    coords: { lat: 9.8821, lng: 78.0815 },
    popularSpots: ['Main Auditorium', 'ECE Department Complex', 'College Library', 'Student Canteen']
  },
  {
    id: 'coimbatore-cit',
    name: 'Coimbatore Institute of Technology',
    district: 'Coimbatore',
    category: 'University / College',
    coords: { lat: 11.0284, lng: 77.0275 },
    popularSpots: ['Library Block', 'Mechanical Department', 'Main Canteen', 'Sports Ground']
  },
  {
    id: 'salem-gce',
    name: 'Government College of Engineering',
    district: 'Salem',
    category: 'University / College',
    coords: { lat: 11.7011, lng: 78.1172 },
    popularSpots: ['CSE Department', 'Admin Block', 'Auditorium Quadrangle']
  },
  {
    id: 'tirunelveli-stx',
    name: "St. Xavier's College Palayamkottai",
    district: 'Tirunelveli',
    category: 'University / College',
    coords: { lat: 8.7189, lng: 77.7412 },
    popularSpots: ['Fr. Lebeau Auditorium', 'Central Library', 'Science Block']
  }
];

const INITIAL_CATEGORIES: Category[] = [
  { id: 1, category_name: 'Electronics & Laptops' },
  { id: 2, category_name: 'Documents & College ID' },
  { id: 3, category_name: 'Keys & Keychains' },
  { id: 4, category_name: 'Wallets & Purses' },
  { id: 5, category_name: 'Bags & Backpacks' },
  { id: 6, category_name: 'Jewelry & Watches' },
  { id: 7, category_name: 'Books & Stationery' },
];

const INITIAL_ITEMS: Item[] = [
  {
    id: 1,
    type: 'lost',
    user_name: 'Pravin',
    category_id: 1,
    item_name: 'Dell XPS 13 Laptop in Black Sleeve',
    description: 'Black Dell laptop with BCA college sticker on top cover. Lost near Main Auditorium during annual fest.',
    image: 'https://images.unsplash.com/photo-1588872657578-7efd1f1555ed?w=600&auto=format&fit=crop&q=80',
    location: 'Anna University Guindy, CEG Main Auditorium, Chennai, Tamil Nadu',
    date: '2026-07-20',
    reward: 1000,
    contact: '9876543210',
    status: 'Approved',
    created_at: '2026-07-20 10:30:00'
  },
  {
    id: 2,
    type: 'lost',
    user_name: 'Aditiyan',
    category_id: 2,
    item_name: 'College ID Card & Metro Pass',
    description: 'Student ID Card bearing Name: Priya Verma, Roll No: BCA-2023-45 inside a blue lanyard with metro card.',
    image: 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?w=600&auto=format&fit=crop&q=80',
    location: 'NIT Trichy Octagon Computer Center, Tiruchirappalli, Tamil Nadu',
    date: '2026-07-22',
    reward: 0,
    contact: '9812345678',
    status: 'Approved',
    created_at: '2026-07-22 14:15:00'
  },
  {
    id: 3,
    type: 'found',
    user_name: 'riyaz',
    category_id: 1,
    item_name: 'Apple AirPods Pro in White Case',
    description: 'Found AirPods Pro case on bench near cafeteria. Contains serial number printed on inside lid.',
    image: 'https://images.unsplash.com/photo-1600294037681-c80b4cb5b434?w=600&auto=format&fit=crop&q=80',
    location: 'PSG Tech Peelamedu, Main Canteen Outer Bench, Coimbatore, Tamil Nadu',
    date: '2026-07-21',
    contact: '9812345678',
    status: 'Approved',
    created_at: '2026-07-21 16:45:00'
  },
  {
    id: 4,
    type: 'found',
    user_name: 'raja',
    category_id: 4,
    item_name: 'Brown Leather Fastrack Wallet',
    description: 'Brown leather wallet containing canteen tokens, driver license, and emergency cash.',
    image: 'https://images.unsplash.com/photo-1627123424574-724758594e93?w=600&auto=format&fit=crop&q=80',
    location: 'VIT University Technology Tower (TT), Katpadi, Vellore, Tamil Nadu',
    date: '2026-07-24',
    contact: '9876543210',
    status: 'Approved',
    created_at: '2026-07-24 11:20:00'
  },
  {
    id: 5,
    type: 'lost',
    user_name: 'sanjay',
    category_id: 3,
    item_name: 'Silver Bike Keys with Superman Ring',
    description: 'Key ring with metallic Superman logo and 2 keys for Honda Shine bike.',
    image: 'https://images.unsplash.com/photo-1582139329536-e7284fece509?w=600&auto=format&fit=crop&q=80',
    location: 'SASTRA Campus Nirman Vihar, Thanjavur, Tamil Nadu',
    date: '2026-07-25',
    reward: 200,
    contact: '9876543210',
    status: 'Pending',
    created_at: '2026-07-25 09:10:00'
  },
  {
    id: 6,
    type: 'found',
    user_name: 'ganesh',
    category_id: 2,
    item_name: 'Casio Scientific Calculator & BCA Student ID',
    description: 'Found Casio fx-991EX scientific calculator along with BCA student ID card near BCA Computer Lab Block.',
    image: 'https://images.unsplash.com/photo-1594980596870-8aa52a78d8cd?w=600&auto=format&fit=crop&q=80',
    location: 'Arignar Anna Govt Arts College, BCA Lab Block, Cheyyar, Tamil Nadu',
    date: '2026-07-26',
    reward: 0,
    contact: '9840123456',
    status: 'Approved',
    created_at: '2026-07-26 11:20:00'
  }
];

export default function App() {
  const [role, setRole] = useState<Role>('guest');
  const [activeTab, setActiveTab] = useState<'home' | 'search' | 'report_lost' | 'report_found' | 'user_dash' | 'admin_dash' | 'about' | 'contact' | 'php_files'>('home');
  const [categories, setCategories] = useState<Category[]>(INITIAL_CATEGORIES);
  const [items, setItems] = useState<Item[]>(INITIAL_ITEMS);

  // Search Filter state
  const [searchQuery, setSearchQuery] = useState('');
  const [searchCategory, setSearchCategory] = useState('');
  const [searchType, setSearchType] = useState<'all' | 'lost' | 'found'>('all');
  const [searchDistrict, setSearchDistrict] = useState<string>('All Tamil Nadu');

  // Tamil Nadu Map Location Picker States
  const [lostLocation, setLostLocation] = useState<string>('Arignar Anna Govt Arts College, Cheyyar, Tamil Nadu');
  const [foundLocation, setFoundLocation] = useState<string>('PSG Tech Peelamedu, Main Canteen Outer Bench, Coimbatore, Tamil Nadu');
  const [showTNMapModal, setShowTNMapModal] = useState<boolean>(false);
  const [mapTargetForm, setMapTargetForm] = useState<'lost' | 'found' | 'search' | null>(null);
  const [activeMapDistrict, setActiveMapDistrict] = useState<string>('Cheyyar');
  const [selectedMapHotspot, setSelectedMapHotspot] = useState<TNLocationHotspot | null>(TAMILNADU_HOTSPOTS[0]);
  const [customSpotDetail, setCustomSpotDetail] = useState<string>('');
  
  // Dynamic Map Pin & Visual Controls
  const [mapPinPulseKey, setMapPinPulseKey] = useState<number>(0);
  const [mapZoom, setMapZoom] = useState<number>(15);
  const [mapViewType, setMapViewType] = useState<'m' | 'k'>('m'); // 'm' for standard, 'k' for satellite
  const [showPinTooltip, setShowPinTooltip] = useState<boolean>(true);

  const applyTNLocation = (locationText: string) => {
    if (mapTargetForm === 'lost') {
      setLostLocation(locationText);
      showToast(`Location set to: ${locationText}`);
    } else if (mapTargetForm === 'found') {
      setFoundLocation(locationText);
      showToast(`Location set to: ${locationText}`);
    } else if (mapTargetForm === 'search') {
      setSearchQuery(locationText);
      showToast(`Search location updated: ${locationText}`);
    }
    setShowTNMapModal(false);
  };

  // Selected item modal & Printable Poster Modal
  const [selectedItem, setSelectedItem] = useState<Item | null>(null);
  const [showPosterModal, setShowPosterModal] = useState<Item | null>(null);
  const [copiedLinkId, setCopiedLinkId] = useState<number | null>(null);

  // Auto-open item detail modal if URL contains ?item=ID
  useEffect(() => {
    const params = new URLSearchParams(window.location.search);
    const itemId = params.get('item');
    if (itemId) {
      const found = items.find(i => i.id === Number(itemId));
      if (found) {
        setSelectedItem(found);
      }
    }
  }, [items]);

  // Helper to generate direct item link URL
  const getItemDirectUrl = (itemId: number) => {
    const url = new URL(window.location.href);
    url.searchParams.set('item', String(itemId));
    return url.toString();
  };

  const handleCopyLink = (itemId: number) => {
    const directUrl = getItemDirectUrl(itemId);
    navigator.clipboard.writeText(directUrl);
    setCopiedLinkId(itemId);
    showToast('Direct item QR link copied to clipboard!');
    setTimeout(() => setCopiedLinkId(null), 3000);
  };

  const handlePrintPoster = () => {
    window.print();
  };

  // New Category Input
  const [newCatName, setNewCatName] = useState('');

  // Direct Image Upload State
  const [lostImagePreview, setLostImagePreview] = useState<string>('');
  const [foundImagePreview, setFoundImagePreview] = useState<string>('');

  // Handle Image File Selection
  const handleImageFileChange = (
    e: React.ChangeEvent<HTMLInputElement>,
    setPreview: (url: string) => void
  ) => {
    const file = e.target.files?.[0];
    if (file) {
      if (file.size > 5 * 1024 * 1024) {
        showToast('Image file size must be under 5MB');
        return;
      }
      const reader = new FileReader();
      reader.onloadend = () => {
        setPreview(reader.result as string);
        showToast('Image attached successfully!');
      };
      reader.readAsDataURL(file);
    }
  };

  // Toast notification
  const [toast, setToast] = useState<string | null>(null);

  const showToast = (msg: string) => {
    setToast(msg);
    setTimeout(() => setToast(null), 4000);
  };

  // Add Category Handler
  const handleAddCategory = (e: React.FormEvent) => {
    e.preventDefault();
    if (!newCatName.trim()) return;
    const newCat: Category = {
      id: Date.now(),
      category_name: newCatName.trim()
    };
    setCategories([...categories, newCat]);
    setNewCatName('');
    showToast('New category added successfully!');
  };

  // Submit Item Report
  const handleReportSubmit = (type: 'lost' | 'found', e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    const formData = new FormData(e.currentTarget);
    const uploadedImage = type === 'lost' ? lostImagePreview : foundImagePreview;
    const urlImage = String(formData.get('image') || '');
    const finalImage = uploadedImage || urlImage || 'https://images.unsplash.com/photo-1584438784894-089d6a62b8fa?w=600&auto=format&fit=crop&q=80';
    
    const newItem: Item = {
      id: Date.now(),
      type,
      user_name: role === 'user' ? 'Rahul Sharma' : 'Guest Reporter',
      category_id: Number(formData.get('category_id')),
      item_name: String(formData.get('item_name')),
      description: String(formData.get('description')),
      image: finalImage,
      location: String(formData.get('location')),
      date: String(formData.get('date')),
      reward: type === 'lost' ? Number(formData.get('reward') || 0) : undefined,
      contact: String(formData.get('contact')),
      status: 'Pending',
      created_at: new Date().toISOString()
    };

    setItems([newItem, ...items]);
    if (type === 'lost') setLostImagePreview('');
    if (type === 'found') setFoundImagePreview('');
    showToast(`Report submitted successfully! It is pending admin approval.`);
    if (role === 'guest') {
      setRole('user');
    }
    setActiveTab('user_dash');
  };

  // Item Status Action
  const updateItemStatus = (id: number, status: 'Approved' | 'Rejected' | 'Claimed') => {
    setItems(items.map(i => i.id === id ? { ...i, status } : i));
    showToast(`Item status updated to ${status}.`);
  };

  // Delete Item Action
  const deleteItem = (id: number) => {
    setItems(items.filter(i => i.id !== id));
    showToast(`Report deleted successfully.`);
  };

  // Filtered Items for Search Page
  const filteredItems = items.filter(item => {
    if (item.status !== 'Approved' && item.status !== 'Claimed' && role !== 'admin') return false;
    
    if (searchQuery) {
      const q = searchQuery.toLowerCase();
      const matchName = item.item_name.toLowerCase().includes(q);
      const matchDesc = item.description.toLowerCase().includes(q);
      const matchLoc = item.location.toLowerCase().includes(q);
      if (!matchName && !matchDesc && !matchLoc) return false;
    }

    if (searchCategory && item.category_id !== Number(searchCategory)) {
      return false;
    }

    if (searchDistrict && searchDistrict !== 'All Tamil Nadu') {
      const dist = searchDistrict.toLowerCase();
      if (!item.location.toLowerCase().includes(dist)) return false;
    }

    if (searchType !== 'all' && item.type !== searchType) {
      return false;
    }

    return true;
  });

  const totalLostCount = items.filter(i => i.type === 'lost' && i.status === 'Approved').length;
  const totalFoundCount = items.filter(i => i.type === 'found' && i.status === 'Approved').length;
  const totalClaimedCount = items.filter(i => i.status === 'Claimed').length;

  return (
    <div className="min-h-screen flex flex-col font-sans text-slate-200 selection:bg-indigo-500 selection:text-white" style={{ backgroundColor: '#05050a', backgroundImage: 'radial-gradient(circle at 0% 0%, #1a1a2e 0%, transparent 50%), radial-gradient(circle at 100% 100%, #16213e 0%, transparent 50%)' }}>
      
      {/* Toast Notification */}
      {toast && (
        <div className="fixed top-20 right-5 z-50 bg-slate-900/90 text-white px-5 py-3 rounded-xl shadow-2xl shadow-indigo-500/20 flex items-center gap-3 border border-indigo-500/40 backdrop-blur-xl animate-bounce">
          <CheckCircle className="w-5 h-5 text-emerald-400" />
          <span className="text-sm font-medium">{toast}</span>
        </div>
      )}

      {/* Top Banner / Role Switcher Header Bar */}
      <div className="bg-black/60 text-slate-400 text-xs py-2 px-4 border-b border-white/10 flex flex-wrap justify-between items-center gap-2 backdrop-blur-md">
        <div className="flex items-center gap-2">
          <span className="bg-indigo-600 text-white px-2 py-0.5 rounded font-bold text-[10px] tracking-wide shadow-sm shadow-indigo-500/20">BCA Mini Project</span>
          <span className="text-slate-400">PHP 8 + MySQL XAMPP Architecture Portal</span>
        </div>
        <div className="flex items-center gap-3">
          <span className="text-slate-400">Current Role:</span>
          <button
            onClick={() => { setRole('guest'); setActiveTab('home'); }}
            className={`px-2.5 py-1 rounded-lg text-xs transition border ${role === 'guest' ? 'bg-indigo-600 border-indigo-500 text-white font-bold shadow-lg shadow-indigo-500/20' : 'bg-white/5 border-white/10 text-slate-400 hover:text-white hover:bg-white/10'}`}
          >
            Guest View
          </button>
          <button
            onClick={() => { setRole('user'); setActiveTab('user_dash'); }}
            className={`px-2.5 py-1 rounded-lg text-xs transition border ${role === 'user' ? 'bg-emerald-600 border-emerald-500 text-white font-bold shadow-lg shadow-emerald-500/20' : 'bg-white/5 border-white/10 text-slate-400 hover:text-white hover:bg-white/10'}`}
          >
            User View (Rahul)
          </button>
          <button
            onClick={() => { setRole('admin'); setActiveTab('admin_dash'); }}
            className={`px-2.5 py-1 rounded-lg text-xs transition border ${role === 'admin' ? 'bg-amber-600 border-amber-500 text-white font-bold shadow-lg shadow-amber-500/20' : 'bg-white/5 border-white/10 text-slate-400 hover:text-white hover:bg-white/10'}`}
          >
            Admin View
          </button>
          <button
            onClick={() => setActiveTab('php_files')}
            className="bg-indigo-600/80 hover:bg-indigo-600 border border-indigo-500/30 text-white px-2.5 py-1 rounded-lg text-xs font-semibold flex items-center gap-1 shadow-md transition"
          >
            <FolderOpen className="w-3.5 h-3.5" /> View Project Files
          </button>
        </div>
      </div>

      {/* Main Navbar */}
      <header 
        className="bg-cover bg-center bg-no-repeat border-b border-white/10 text-white sticky top-0 z-40 backdrop-blur-xl"
        style={{
          backgroundImage: `linear-gradient(to right, rgba(15, 5, 10, 0.45), rgba(5, 5, 10, 0.75)), url(${nebulaBg})`
        }}
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex justify-between items-center">
          <div 
            className="flex items-center gap-3 cursor-pointer group"
            onClick={() => setActiveTab('home')}
          >
            <div className="w-10 h-10 rounded-xl overflow-hidden shadow-xl shadow-indigo-500/30 ring-2 ring-indigo-500/40 border border-white/20 group-hover:scale-105 group-hover:ring-indigo-400 transition-all flex items-center justify-center bg-slate-900">
              <img src={portalLogo} alt="Immortal Find Logo" className="w-full h-full object-cover group-hover:brightness-110 transition-all" referrerPolicy="no-referrer" />
            </div>
            <div>
              <span className="text-xl font-bold tracking-tight text-white">Immortal<span className="text-indigo-400 font-light">Find</span></span>
              <span className="block text-[10px] text-slate-400 font-medium tracking-wider uppercase">Campus Portal</span>
            </div>
          </div>

          <nav className="hidden md:flex items-center gap-1 text-sm font-medium">
            <button
              onClick={() => setActiveTab('home')}
              className={`px-3.5 py-2 rounded-xl transition ${activeTab === 'home' ? 'text-indigo-400 bg-white/10 border border-white/10 font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5'}`}
            >
              Home
            </button>
            <button
              onClick={() => setActiveTab('search')}
              className={`px-3.5 py-2 rounded-xl transition ${activeTab === 'search' ? 'text-indigo-400 bg-white/10 border border-white/10 font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5'}`}
            >
              Search Directory
            </button>
            <button
              onClick={() => setActiveTab('about')}
              className={`px-3.5 py-2 rounded-xl transition ${activeTab === 'about' ? 'text-indigo-400 bg-white/10 border border-white/10 font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5'}`}
            >
              About Project
            </button>
            <button
              onClick={() => setActiveTab('contact')}
              className={`px-3.5 py-2 rounded-xl transition ${activeTab === 'contact' ? 'text-indigo-400 bg-white/10 border border-white/10 font-semibold' : 'text-slate-400 hover:text-white hover:bg-white/5'}`}
            >
              Contact
            </button>
          </nav>

          <div className="flex items-center gap-2">
            <button
              onClick={() => setActiveTab('report_lost')}
              className="bg-rose-600/90 hover:bg-rose-600 text-white text-xs sm:text-sm font-semibold px-3.5 py-2 rounded-xl shadow-lg shadow-rose-600/20 border border-rose-500/30 transition flex items-center gap-1.5"
            >
              <ShieldAlert className="w-4 h-4" /> Report Lost
            </button>
            <button
              onClick={() => setActiveTab('report_found')}
              className="bg-emerald-600/90 hover:bg-emerald-600 text-white text-xs sm:text-sm font-semibold px-3.5 py-2 rounded-xl shadow-lg shadow-emerald-600/20 border border-emerald-500/30 transition flex items-center gap-1.5"
            >
              <HeartHandshake className="w-4 h-4" /> Report Found
            </button>

            {role === 'user' && (
              <button
                onClick={() => setActiveTab('user_dash')}
                className="ml-2 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold px-3.5 py-2 rounded-xl border border-indigo-500/30 flex items-center gap-1 shadow-lg shadow-indigo-500/20"
              >
                <LayoutDashboard className="w-4 h-4" /> User Dashboard
              </button>
            )}

            {role === 'admin' && (
              <button
                onClick={() => setActiveTab('admin_dash')}
                className="ml-2 bg-amber-500 hover:bg-amber-400 text-slate-950 text-xs font-bold px-3.5 py-2 rounded-xl border border-amber-400/30 flex items-center gap-1 shadow-lg shadow-amber-500/20"
              >
                <ShieldCheck className="w-4 h-4" /> Admin Panel
              </button>
            )}
          </div>
        </div>
      </header>

      {/* BODY CONTENT AREA */}
      <main className="flex-grow">
        
        {/* PAGE 1: HOME LANDING PAGE */}
        {activeTab === 'home' && (
          <div>
            {/* Hero Section */}
            <section 
              className="py-16 px-4 relative overflow-hidden border-b border-white/10 backdrop-blur-md bg-cover bg-center bg-no-repeat"
              style={{
                backgroundImage: `linear-gradient(to bottom, rgba(5, 5, 10, 0.65), rgba(5, 5, 10, 0.85)), url(${redHeroBg})`
              }}
            >
              <div className="max-w-5xl mx-auto text-center relative z-10">
                <span className="inline-flex items-center gap-2 bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-semibold px-4 py-1.5 rounded-full mb-6 backdrop-blur-sm">
                  <Award className="w-4 h-4 text-indigo-400" /> BCA Final Year Mini Project System
                </span>
                <h1 className="text-4xl sm:text-5xl font-extrabold tracking-tight mb-4 leading-tight text-white">
                  Misplaced Something? Found an Item?
                </h1>
                <p className="text-slate-400 text-base sm:text-lg max-w-2xl mx-auto mb-8 font-normal leading-relaxed">
                  <strong className="animate-subtle-entrance text-indigo-200 font-bold px-3 py-1 inline-block my-1 rounded-lg bg-indigo-500/20 border border-indigo-400/40 shadow-sm shadow-indigo-500/30">Welcome back Aasif</strong> — A centralized campus platform designed to report lost belongings or reunite found possessions with their rightful owners quickly and securely.
                </p>

                <div className="flex flex-wrap justify-center gap-4 mb-10">
                  <button
                    onClick={() => setActiveTab('report_lost')}
                    className="group bg-gradient-to-r from-rose-600 to-pink-600 hover:brightness-110 text-white font-bold px-6 py-3 rounded-xl shadow-xl shadow-rose-500/10 border border-rose-500/30 transition transform hover:-translate-y-0.5 flex items-center gap-2 text-sm"
                  >
                    <ShieldAlert className="w-5 h-5 transition-transform duration-300 group-hover:scale-125 group-hover:-rotate-12 text-rose-100" /> Report Lost Item
                  </button>
                  <button
                    onClick={() => setActiveTab('report_found')}
                    className="group bg-gradient-to-r from-emerald-600 to-teal-600 hover:brightness-110 text-white font-bold px-6 py-3 rounded-xl shadow-xl shadow-emerald-500/10 border border-emerald-500/30 transition transform hover:-translate-y-0.5 flex items-center gap-2 text-sm"
                  >
                    <HeartHandshake className="w-5 h-5 transition-transform duration-300 group-hover:scale-125 group-hover:rotate-6 text-emerald-100" /> Report Found Item
                  </button>
                  <button
                    onClick={() => setActiveTab('search')}
                    className="group bg-white/5 hover:bg-white/10 text-white font-semibold px-6 py-3 rounded-xl border border-white/10 transition flex items-center gap-2 text-sm backdrop-blur-md"
                  >
                    <Search className="w-5 h-5 transition-transform duration-300 group-hover:scale-115 text-indigo-300" /> Browse Directory
                  </button>
                </div>

                {/* Quick Search Bar */}
                <div className="p-6 rounded-2xl bg-slate-900/80 border border-white/10 backdrop-blur-md shadow-2xl max-w-3xl mx-auto">
                  <div className="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div className="relative">
                      <Search className="w-4 h-4 absolute left-3.5 top-3.5 text-slate-500" />
                      <input
                        type="text"
                        placeholder="Item name (e.g. Laptop, ID)..."
                        value={searchQuery}
                        onChange={e => setSearchQuery(e.target.value)}
                        className="w-full pl-9 pr-3 py-2.5 bg-slate-950/70 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                      />
                    </div>
                    <div>
                      <select
                        value={searchCategory}
                        onChange={e => setSearchCategory(e.target.value)}
                        className="w-full px-3 py-2.5 bg-slate-950/70 border border-white/10 rounded-xl text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                      >
                        <option value="" className="bg-slate-900 text-white">All Categories</option>
                        {categories.map(c => (
                          <option key={c.id} value={c.id} className="bg-slate-900 text-white">{c.category_name}</option>
                        ))}
                      </select>
                    </div>
                    <div>
                      <button
                        onClick={() => setActiveTab('search')}
                        className="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:brightness-110 text-white font-bold py-2.5 rounded-xl shadow-lg shadow-indigo-500/20 border border-indigo-500/30 transition text-sm flex items-center justify-center gap-2"
                      >
                        <Search className="w-4 h-4" /> Search Directory
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </section>

            {/* Live Stats Bar */}
            <section className="max-w-7xl mx-auto px-4 sm:px-6 -mt-8 relative z-20 mb-12">
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div className="p-5 rounded-2xl bg-rose-500/10 border border-rose-500/20 backdrop-blur-md shadow-lg flex items-center justify-between">
                  <div>
                    <span className="text-xs font-bold text-rose-400 uppercase tracking-widest block mb-1">Total Lost Items</span>
                    <span className="text-3xl font-black text-white">{totalLostCount}</span>
                  </div>
                  <div className="p-3 bg-rose-500/20 text-rose-400 rounded-2xl border border-rose-500/30">
                    <ShieldAlert className="w-7 h-7" />
                  </div>
                </div>

                <div className="p-5 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 backdrop-blur-md shadow-lg flex items-center justify-between">
                  <div>
                    <span className="text-xs font-bold text-emerald-400 uppercase tracking-widest block mb-1">Total Found Items</span>
                    <span className="text-3xl font-black text-white">{totalFoundCount}</span>
                  </div>
                  <div className="p-3 bg-emerald-500/20 text-emerald-400 rounded-2xl border border-emerald-500/30">
                    <HeartHandshake className="w-7 h-7" />
                  </div>
                </div>

                <div className="p-5 rounded-2xl bg-indigo-500/10 border border-indigo-500/20 backdrop-blur-md shadow-lg flex items-center justify-between">
                  <div>
                    <span className="text-xs font-bold text-indigo-400 uppercase tracking-widest block mb-1">Reunited Items</span>
                    <span className="text-3xl font-black text-white">{totalClaimedCount}</span>
                  </div>
                  <div className="p-3 bg-indigo-500/20 text-indigo-400 rounded-2xl border border-indigo-500/30">
                    <CheckCircle className="w-7 h-7" />
                  </div>
                </div>
              </div>
            </section>

            {/* Recent Lost Items Grid */}
            <section className="max-w-7xl mx-auto px-4 sm:px-6 mb-12">
              <div className="flex justify-between items-center mb-6">
                <div>
                  <h2 className="text-2xl font-bold text-white flex items-center gap-2">
                    <ShieldAlert className="w-6 h-6 text-rose-500" /> Recent Lost Items
                  </h2>
                  <p className="text-slate-400 text-xs sm:text-sm mt-0.5">Misplaced possessions waiting to be found</p>
                </div>
                <button
                  onClick={() => { setSearchType('lost'); setActiveTab('search'); }}
                  className="text-rose-400 hover:text-rose-300 text-xs sm:text-sm font-semibold flex items-center gap-1 transition"
                >
                  View All Lost &rarr;
                </button>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {items.filter(i => i.type === 'lost' && i.status === 'Approved').slice(0, 3).map(item => (
                  <div key={item.id} className="bg-white/5 border border-white/10 hover:bg-white/10 rounded-2xl p-4 flex flex-col justify-between transition-all duration-300 backdrop-blur-md shadow-lg hover:shadow-indigo-500/10 group">
                    <div className="h-48 bg-slate-950/80 rounded-xl relative overflow-hidden mb-4 border border-white/5">
                      <span className="absolute top-3 left-3 bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs font-bold px-3 py-1 rounded-full shadow-md z-10 flex items-center gap-1 backdrop-blur-md">
                        <ShieldAlert className="w-3.5 h-3.5" /> LOST
                      </span>
                      <img
                        src={item.image}
                        alt={item.item_name}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                      />
                    </div>
                    <div className="flex-grow flex flex-col justify-between">
                      <div>
                        <div className="flex justify-between items-center mb-2">
                          <span className="text-[11px] font-semibold bg-white/5 text-slate-300 border border-white/10 px-2.5 py-0.5 rounded-md">
                            {categories.find(c => c.id === item.category_id)?.category_name}
                          </span>
                          <span className="text-xs text-slate-400 flex items-center gap-1">
                            <Calendar className="w-3.5 h-3.5 text-slate-500" /> {item.date}
                          </span>
                        </div>
                        <h3 className="text-base font-semibold text-white mb-1 group-hover:text-indigo-400 transition">{item.item_name}</h3>
                        <p className="text-slate-400 text-xs line-clamp-2 mb-4 leading-relaxed">{item.description}</p>
                      </div>

                      <div>
                        <div className="pt-3 border-t border-white/10 flex justify-between items-center text-xs text-slate-400 mb-4">
                          <span className="flex items-center gap-1"><MapPin className="w-3.5 h-3.5 text-rose-400" /> {item.location}</span>
                          {item.reward ? (
                            <span className="bg-amber-500/20 border border-amber-500/30 text-amber-300 font-bold px-2 py-0.5 rounded text-[11px]">Reward: ₹{item.reward}</span>
                          ) : null}
                        </div>
                        <div className="flex items-center gap-2">
                          <button
                            onClick={() => setSelectedItem(item)}
                            className="flex-grow bg-indigo-600/80 hover:bg-indigo-600 text-white text-xs font-semibold py-2 rounded-xl transition border border-indigo-500/30 flex items-center justify-center gap-1.5 shadow-md"
                          >
                            <Eye className="w-4 h-4" /> View Details
                          </button>
                          <button
                            type="button"
                            onClick={() => setShowPosterModal(item)}
                            className="bg-white/10 hover:bg-white/20 text-rose-300 hover:text-white text-xs font-semibold p-2 rounded-xl transition border border-white/10 flex items-center justify-center gap-1"
                            title="Generate Printable QR Poster"
                          >
                            <QrCode className="w-4 h-4" /> Poster
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </section>

            {/* Recent Found Items Grid */}
            <section className="max-w-7xl mx-auto px-4 sm:px-6 mb-16">
              <div className="flex justify-between items-center mb-6">
                <div>
                  <h2 className="text-2xl font-bold text-white flex items-center gap-2">
                    <HeartHandshake className="w-6 h-6 text-emerald-500" /> Recent Found Items
                  </h2>
                  <p className="text-slate-400 text-xs sm:text-sm mt-0.5">Turned in items ready to be claimed</p>
                </div>
                <button
                  onClick={() => { setSearchType('found'); setActiveTab('search'); }}
                  className="text-emerald-400 hover:text-emerald-300 text-xs sm:text-sm font-semibold flex items-center gap-1 transition"
                >
                  View All Found &rarr;
                </button>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                {items.filter(i => i.type === 'found' && i.status === 'Approved').slice(0, 3).map(item => (
                  <div key={item.id} className="bg-white/5 border border-white/10 hover:bg-white/10 rounded-2xl p-4 flex flex-col justify-between transition-all duration-300 backdrop-blur-md shadow-lg hover:shadow-indigo-500/10 group">
                    <div className="h-48 bg-slate-950/80 rounded-xl relative overflow-hidden mb-4 border border-white/5">
                      <span className="absolute top-3 left-3 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 text-xs font-bold px-3 py-1 rounded-full shadow-md z-10 flex items-center gap-1 backdrop-blur-md">
                        <HeartHandshake className="w-3.5 h-3.5" /> FOUND
                      </span>
                      <img
                        src={item.image}
                        alt={item.item_name}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                      />
                    </div>
                    <div className="flex-grow flex flex-col justify-between">
                      <div>
                        <div className="flex justify-between items-center mb-2">
                          <span className="text-[11px] font-semibold bg-white/5 text-slate-300 border border-white/10 px-2.5 py-0.5 rounded-md">
                            {categories.find(c => c.id === item.category_id)?.category_name}
                          </span>
                          <span className="text-xs text-slate-400 flex items-center gap-1">
                            <Calendar className="w-3.5 h-3.5 text-slate-500" /> {item.date}
                          </span>
                        </div>
                        <h3 className="text-base font-semibold text-white mb-1 group-hover:text-emerald-400 transition">{item.item_name}</h3>
                        <p className="text-slate-400 text-xs line-clamp-2 mb-4 leading-relaxed">{item.description}</p>
                      </div>

                      <div>
                        <div className="pt-3 border-t border-white/10 flex justify-between items-center text-xs text-slate-400 mb-4">
                          <span className="flex items-center gap-1"><MapPin className="w-3.5 h-3.5 text-emerald-400" /> {item.location}</span>
                          <span className="text-emerald-400 font-semibold flex items-center gap-0.5"><Phone className="w-3 h-3" /> Verified</span>
                        </div>
                        <div className="flex items-center gap-2">
                          <button
                            onClick={() => setSelectedItem(item)}
                            className="flex-grow bg-emerald-600/80 hover:bg-emerald-600 text-white text-xs font-semibold py-2 rounded-xl transition border border-emerald-500/30 flex items-center justify-center gap-1.5 shadow-md"
                          >
                            <Eye className="w-4 h-4" /> View & Claim
                          </button>
                          <button
                            type="button"
                            onClick={() => setShowPosterModal(item)}
                            className="bg-white/10 hover:bg-white/20 text-emerald-300 hover:text-white text-xs font-semibold p-2 rounded-xl transition border border-white/10 flex items-center justify-center gap-1"
                            title="Generate Printable QR Poster"
                          >
                            <QrCode className="w-4 h-4" /> Poster
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
            </section>
          </div>
        )}

        {/* PAGE 2: SEARCH DIRECTORY PAGE */}
        {activeTab === 'search' && (
          <div className="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div className="mb-8">
              <h1 className="text-3xl font-extrabold text-white flex items-center gap-3">
                <Search className="w-8 h-8 text-indigo-400" /> Search Directory
              </h1>
              <p className="text-slate-400 text-sm mt-1">Filter items by keyword, category, date, or status</p>
            </div>

            {/* Filter Card */}
            <div className="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-xl mb-8 space-y-4">
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Keyword Search</label>
                  <input
                    type="text"
                    placeholder="Laptop, wallet, keys, college..."
                    value={searchQuery}
                    onChange={e => setSearchQuery(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  />
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Category Filter</label>
                  <select
                    value={searchCategory}
                    onChange={e => setSearchCategory(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  >
                    <option value="" className="bg-slate-900 text-white">All Categories</option>
                    {categories.map(c => (
                      <option key={c.id} value={c.id} className="bg-slate-900 text-white">{c.category_name}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Tamil Nadu District</label>
                  <select
                    value={searchDistrict}
                    onChange={e => setSearchDistrict(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  >
                    {TAMILNADU_DISTRICTS.map(d => (
                      <option key={d} value={d} className="bg-slate-900 text-white">{d}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Report Type</label>
                  <select
                    value={searchType}
                    onChange={e => setSearchType(e.target.value as 'all' | 'lost' | 'found')}
                    className="w-full px-3 py-2 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  >
                    <option value="all" className="bg-slate-900 text-white">All (Lost & Found)</option>
                    <option value="lost" className="bg-slate-900 text-white">Lost Items Only</option>
                    <option value="found" className="bg-slate-900 text-white">Found Items Only</option>
                  </select>
                </div>
              </div>

              <div className="flex flex-wrap items-center justify-between gap-3 pt-2 border-t border-white/10">
                <button
                  type="button"
                  onClick={() => {
                    setMapTargetForm('search');
                    setShowTNMapModal(true);
                  }}
                  className="bg-indigo-600/30 hover:bg-indigo-600/50 text-indigo-200 border border-indigo-400/30 text-xs font-semibold px-4 py-2 rounded-xl transition flex items-center gap-2"
                >
                  <Map className="w-4 h-4 text-indigo-400" /> Select Location on Tamil Nadu Map
                </button>

                <button
                  onClick={() => { setSearchQuery(''); setSearchCategory(''); setSearchType('all'); setSearchDistrict('All Tamil Nadu'); }}
                  className="bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 font-semibold px-4 py-2 rounded-xl text-xs transition flex items-center gap-1.5"
                >
                  <RefreshCw className="w-3.5 h-3.5" /> Reset Filters
                </button>
              </div>
            </div>

            {/* Results Grid */}
            <div className="mb-4">
              <span className="text-sm font-bold text-slate-400">Showing {filteredItems.length} result(s)</span>
            </div>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
              {filteredItems.map(item => (
                <div key={item.id} className="bg-white/5 border border-white/10 hover:bg-white/10 rounded-2xl p-4 flex flex-col justify-between transition-all duration-300 backdrop-blur-md shadow-lg group">
                  <div className="h-48 bg-slate-950/80 rounded-xl relative overflow-hidden mb-4 border border-white/5">
                    <span className={`absolute top-3 left-3 text-xs font-bold px-3 py-1 rounded-full shadow-md z-10 flex items-center gap-1 backdrop-blur-md ${item.type === 'lost' ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'}`}>
                      {item.type === 'lost' ? <ShieldAlert className="w-3.5 h-3.5" /> : <HeartHandshake className="w-3.5 h-3.5" />}
                      {item.type.toUpperCase()}
                    </span>
                    {item.status === 'Claimed' && (
                      <span className="absolute top-3 right-3 bg-indigo-500/30 text-indigo-300 border border-indigo-500/40 text-[11px] font-bold px-2.5 py-1 rounded-full shadow-md z-10 backdrop-blur-md">
                        REUNITED / CLAIMED
                      </span>
                    )}
                    <img src={item.image} alt={item.item_name} className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                  </div>
                  <div className="flex-grow flex flex-col justify-between">
                    <div>
                      <div className="flex justify-between items-center mb-2">
                        <span className="text-[11px] font-semibold bg-white/5 text-slate-300 border border-white/10 px-2.5 py-0.5 rounded-md">
                          {categories.find(c => c.id === item.category_id)?.category_name}
                        </span>
                        <span className="text-xs text-slate-400">{item.date}</span>
                      </div>
                      <h3 className="text-base font-semibold text-white mb-1 group-hover:text-indigo-400 transition">{item.item_name}</h3>
                      <p className="text-slate-400 text-xs line-clamp-2 mb-4 leading-relaxed">{item.description}</p>
                    </div>

                    <div>
                      <div className="pt-3 border-t border-white/10 flex justify-between items-center text-xs text-slate-400 mb-4">
                        <span className="flex items-center gap-1"><MapPin className="w-3.5 h-3.5 text-indigo-400" /> {item.location}</span>
                        <span className="font-semibold text-slate-300 flex items-center gap-1"><Phone className="w-3 h-3 text-emerald-400" /> {item.contact}</span>
                      </div>
                      <div className="flex items-center gap-2">
                        <button
                          onClick={() => setSelectedItem(item)}
                          className="flex-grow bg-indigo-600/80 hover:bg-indigo-600 text-white text-xs font-semibold py-2.5 rounded-xl transition border border-indigo-500/30 flex items-center justify-center gap-1.5 shadow-md"
                        >
                          <Eye className="w-4 h-4" /> View Details
                        </button>
                        <button
                          type="button"
                          onClick={() => setShowPosterModal(item)}
                          className="bg-white/10 hover:bg-white/20 text-indigo-300 hover:text-white text-xs font-semibold p-2.5 rounded-xl transition border border-white/10 flex items-center justify-center gap-1"
                          title="Generate Printable QR Poster"
                        >
                          <QrCode className="w-4 h-4" /> Poster
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        )}

        {/* PAGE 3: REPORT LOST ITEM FORM */}
        {activeTab === 'report_lost' && (
          <div className="max-w-3xl mx-auto px-4 py-10">
            <div className="p-6 sm:p-8 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl">
              <div className="flex items-center gap-3 mb-6">
                <div className="p-3 bg-rose-500/20 text-rose-400 rounded-2xl border border-rose-500/30">
                  <ShieldAlert className="w-7 h-7" />
                </div>
                <div>
                  <h1 className="text-2xl font-bold text-white">Report Lost Item</h1>
                  <p className="text-slate-400 text-xs sm:text-sm">Submit accurate item details to help community members identify your item</p>
                </div>
              </div>

              <form onSubmit={e => handleReportSubmit('lost', e)} className="space-y-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Item Name *</label>
                    <input name="item_name" required type="text" placeholder="e.g. Dell XPS 13 Laptop" className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500" />
                  </div>
                  <div>
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Category *</label>
                    <select name="category_id" required className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500">
                      <option value="" className="bg-slate-900 text-white">Select Category</option>
                      {categories.map(c => <option key={c.id} value={c.id} className="bg-slate-900 text-white">{c.category_name}</option>)}
                    </select>
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Detailed Description *</label>
                  <textarea name="description" required rows={3} placeholder="Describe unique features, color, stickers, brand, serial numbers..." className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500" />
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div className="sm:col-span-2 space-y-2">
                    <div className="flex items-center justify-between">
                      <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider">Lost Location (Tamil Nadu) *</label>
                      <button
                        type="button"
                        onClick={() => {
                          setMapTargetForm('lost');
                          setShowTNMapModal(true);
                        }}
                        className="bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/30 text-xs font-semibold px-3 py-1 rounded-lg transition flex items-center gap-1.5"
                      >
                        <Map className="w-3.5 h-3.5 text-rose-400" /> Open Tamil Nadu Map
                      </button>
                    </div>

                    <div className="relative">
                      <MapPin className="w-4 h-4 text-rose-400 absolute left-3.5 top-3.5" />
                      <input
                        name="location"
                        required
                        type="text"
                        value={lostLocation}
                        onChange={(e) => setLostLocation(e.target.value)}
                        placeholder="e.g. Anna University Library, Guindy, Chennai, Tamil Nadu"
                        className="w-full pl-10 pr-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500"
                      />
                    </div>

                    {/* Quick Tamil Nadu Hotspots */}
                    <div className="flex flex-wrap items-center gap-1.5 pt-1">
                      <span className="text-[11px] text-slate-400 font-semibold flex items-center gap-1">
                        <Compass className="w-3 h-3 text-rose-400" /> TN Quick Locations:
                      </span>
                      {['Cheyyar Arts College', 'Indo-American College Cheyyar', 'Cheyyar SIPCOT', 'Anna Univ, Chennai', 'NIT Trichy', 'PSG Tech, Cbe', 'VIT Vellore', 'SRM Chengalpattu', 'SASTRA Tanjore', 'Madurai TCE'].map(spot => (
                        <button
                          key={spot}
                          type="button"
                          onClick={() => setLostLocation(`${spot}, Tamil Nadu`)}
                          className="bg-white/5 hover:bg-rose-500/20 hover:text-rose-200 text-slate-300 border border-white/10 text-[11px] px-2 py-0.5 rounded-md transition"
                        >
                          + {spot}
                        </button>
                      ))}
                    </div>
                  </div>

                  <div className="sm:col-span-2">
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Lost Date *</label>
                    <input name="date" required type="date" defaultValue={new Date().toISOString().split('T')[0]} className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-rose-500" />
                  </div>
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Reward Offered (Optional, ₹)</label>
                    <input name="reward" type="number" placeholder="e.g. 500" className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500" />
                  </div>
                  <div>
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Contact Phone Number *</label>
                    <input name="contact" required type="tel" defaultValue="9876543210" placeholder="10-digit mobile number" className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-rose-500" />
                  </div>
                </div>

                {/* Direct Image Upload or URL for Lost Item */}
                <div className="space-y-3 pt-2">
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider">Item Photo / Image</label>
                  
                  {/* Upload Box */}
                  <div className="p-4 rounded-2xl bg-slate-900/90 border border-dashed border-rose-500/40 hover:border-rose-400/80 transition-colors">
                    {lostImagePreview ? (
                      <div className="relative group rounded-xl overflow-hidden border border-white/20">
                        <img src={lostImagePreview} alt="Lost Item Preview" className="w-full h-48 object-cover" />
                        <div className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                          <label htmlFor="lost-image-input" className="cursor-pointer bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 border border-white/20 backdrop-blur-md">
                            <Upload className="w-4 h-4" /> Change Image
                          </label>
                          <button
                            type="button"
                            onClick={() => setLostImagePreview('')}
                            className="bg-rose-600/80 hover:bg-rose-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 border border-rose-400/30 backdrop-blur-md"
                          >
                            <X className="w-4 h-4" /> Remove
                          </button>
                        </div>
                        <div className="absolute bottom-2 left-2 bg-slate-900/80 backdrop-blur-md border border-white/20 px-2.5 py-1 rounded-md text-[11px] font-semibold text-rose-300 flex items-center gap-1">
                          <Check className="w-3.5 h-3.5 text-emerald-400" /> Direct Image Attached
                        </div>
                      </div>
                    ) : (
                      <div className="text-center py-4">
                        <div className="w-12 h-12 mx-auto mb-3 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center">
                          <Camera className="w-6 h-6" />
                        </div>
                        <p className="text-sm font-semibold text-white mb-1">Click to upload photo directly</p>
                        <p className="text-xs text-slate-400 mb-4">Supports PNG, JPG, WEBP from your device or camera (Max 5MB)</p>
                        <label
                          htmlFor="lost-image-input"
                          className="inline-flex items-center gap-2 bg-gradient-to-r from-rose-600 to-pink-600 hover:brightness-110 text-white text-xs font-bold px-4 py-2.5 rounded-xl cursor-pointer shadow-lg shadow-rose-500/20 border border-rose-400/30 transition"
                        >
                          <Upload className="w-4 h-4" /> Direct Upload Image
                        </label>
                      </div>
                    )}
                    <input
                      id="lost-image-input"
                      type="file"
                      accept="image/*"
                      onChange={(e) => handleImageFileChange(e, setLostImagePreview)}
                      className="hidden"
                    />
                  </div>

                  {/* Fallback URL Input */}
                  <div>
                    <label className="block text-[11px] font-medium text-slate-400 mb-1">Or paste Image URL directly (Optional)</label>
                    <input
                      name="image"
                      type="url"
                      placeholder="https://..."
                      disabled={!!lostImagePreview}
                      className="w-full px-3.5 py-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-rose-500 disabled:opacity-40 disabled:cursor-not-allowed"
                    />
                  </div>
                </div>

                <button type="submit" className="w-full bg-gradient-to-r from-rose-600 to-pink-600 hover:brightness-110 text-white font-bold py-3 rounded-xl shadow-lg shadow-rose-500/20 border border-rose-500/30 transition text-sm flex items-center justify-center gap-2">
                  <ShieldAlert className="w-5 h-5" /> Submit Lost Item Report
                </button>
              </form>
            </div>
          </div>
        )}

        {/* PAGE 4: REPORT FOUND ITEM FORM */}
        {activeTab === 'report_found' && (
          <div className="max-w-3xl mx-auto px-4 py-10">
            <div className="p-6 sm:p-8 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl">
              <div className="flex items-center gap-3 mb-6">
                <div className="p-3 bg-emerald-500/20 text-emerald-400 rounded-2xl border border-emerald-500/30">
                  <HeartHandshake className="w-7 h-7" />
                </div>
                <div>
                  <h1 className="text-2xl font-bold text-white">Report Found Item</h1>
                  <p className="text-slate-400 text-xs sm:text-sm">Help reunite misplaced items with their rightful owners</p>
                </div>
              </div>

              <form onSubmit={e => handleReportSubmit('found', e)} className="space-y-4">
                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Item Name *</label>
                    <input name="item_name" required type="text" placeholder="e.g. AirPods Pro White Case" className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                  </div>
                  <div>
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Category *</label>
                    <select name="category_id" required className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                      <option value="" className="bg-slate-900 text-white">Select Category</option>
                      {categories.map(c => <option key={c.id} value={c.id} className="bg-slate-900 text-white">{c.category_name}</option>)}
                    </select>
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Detailed Description *</label>
                  <textarea name="description" required rows={3} placeholder="Describe where you found it, its condition, color, or distinguishing features..." className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div className="sm:col-span-2 space-y-2">
                    <div className="flex items-center justify-between">
                      <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider">Found Location (Tamil Nadu) *</label>
                      <button
                        type="button"
                        onClick={() => {
                          setMapTargetForm('found');
                          setShowTNMapModal(true);
                        }}
                        className="bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/30 text-xs font-semibold px-3 py-1 rounded-lg transition flex items-center gap-1.5"
                      >
                        <Map className="w-3.5 h-3.5 text-emerald-400" /> Open Tamil Nadu Map
                      </button>
                    </div>

                    <div className="relative">
                      <MapPin className="w-4 h-4 text-emerald-400 absolute left-3.5 top-3.5" />
                      <input
                        name="location"
                        required
                        type="text"
                        value={foundLocation}
                        onChange={(e) => setFoundLocation(e.target.value)}
                        placeholder="e.g. PSG Tech Main Canteen, Peelamedu, Coimbatore, Tamil Nadu"
                        className="w-full pl-10 pr-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500"
                      />
                    </div>

                    {/* Quick Tamil Nadu Hotspots */}
                    <div className="flex flex-wrap items-center gap-1.5 pt-1">
                      <span className="text-[11px] text-slate-400 font-semibold flex items-center gap-1">
                        <Compass className="w-3 h-3 text-emerald-400" /> TN Quick Locations:
                      </span>
                      {['Cheyyar Arts College', 'Indo-American College Cheyyar', 'Cheyyar SIPCOT', 'Anna Univ, Chennai', 'NIT Trichy', 'PSG Tech, Cbe', 'VIT Vellore', 'SRM Chengalpattu', 'SASTRA Tanjore', 'Madurai TCE'].map(spot => (
                        <button
                          key={spot}
                          type="button"
                          onClick={() => setFoundLocation(`${spot}, Tamil Nadu`)}
                          className="bg-white/5 hover:bg-emerald-500/20 hover:text-emerald-200 text-slate-300 border border-white/10 text-[11px] px-2 py-0.5 rounded-md transition"
                        >
                          + {spot}
                        </button>
                      ))}
                    </div>
                  </div>

                  <div className="sm:col-span-2">
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Found Date *</label>
                    <input name="date" required type="date" defaultValue={new Date().toISOString().split('T')[0]} className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                  </div>
                </div>

                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Contact Phone Number *</label>
                  <input name="contact" required type="tel" defaultValue="9812345678" placeholder="10-digit mobile number" className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                </div>

                {/* Direct Image Upload or URL for Found Item */}
                <div className="space-y-3 pt-2">
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider">Item Photo / Image</label>
                  
                  {/* Upload Box */}
                  <div className="p-4 rounded-2xl bg-slate-900/90 border border-dashed border-emerald-500/40 hover:border-emerald-400/80 transition-colors">
                    {foundImagePreview ? (
                      <div className="relative group rounded-xl overflow-hidden border border-white/20">
                        <img src={foundImagePreview} alt="Found Item Preview" className="w-full h-48 object-cover" />
                        <div className="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-3">
                          <label htmlFor="found-image-input" className="cursor-pointer bg-white/20 hover:bg-white/30 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 border border-white/20 backdrop-blur-md">
                            <Upload className="w-4 h-4" /> Change Image
                          </label>
                          <button
                            type="button"
                            onClick={() => setFoundImagePreview('')}
                            className="bg-emerald-600/80 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold flex items-center gap-1.5 border border-emerald-400/30 backdrop-blur-md"
                          >
                            <X className="w-4 h-4" /> Remove
                          </button>
                        </div>
                        <div className="absolute bottom-2 left-2 bg-slate-900/80 backdrop-blur-md border border-white/20 px-2.5 py-1 rounded-md text-[11px] font-semibold text-emerald-300 flex items-center gap-1">
                          <Check className="w-3.5 h-3.5 text-emerald-400" /> Direct Image Attached
                        </div>
                      </div>
                    ) : (
                      <div className="text-center py-4">
                        <div className="w-12 h-12 mx-auto mb-3 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center">
                          <Camera className="w-6 h-6" />
                        </div>
                        <p className="text-sm font-semibold text-white mb-1">Click to upload photo directly</p>
                        <p className="text-xs text-slate-400 mb-4">Supports PNG, JPG, WEBP from your device or camera (Max 5MB)</p>
                        <label
                          htmlFor="found-image-input"
                          className="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-600 to-teal-600 hover:brightness-110 text-white text-xs font-bold px-4 py-2.5 rounded-xl cursor-pointer shadow-lg shadow-emerald-500/20 border border-emerald-400/30 transition"
                        >
                          <Upload className="w-4 h-4" /> Direct Upload Image
                        </label>
                      </div>
                    )}
                    <input
                      id="found-image-input"
                      type="file"
                      accept="image/*"
                      onChange={(e) => handleImageFileChange(e, setFoundImagePreview)}
                      className="hidden"
                    />
                  </div>

                  {/* Fallback URL Input */}
                  <div>
                    <label className="block text-[11px] font-medium text-slate-400 mb-1">Or paste Image URL directly (Optional)</label>
                    <input
                      name="image"
                      type="url"
                      placeholder="https://..."
                      disabled={!!foundImagePreview}
                      className="w-full px-3.5 py-2 bg-slate-900/60 border border-white/10 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 disabled:opacity-40 disabled:cursor-not-allowed"
                    />
                  </div>
                </div>

                <button type="submit" className="w-full bg-gradient-to-r from-emerald-600 to-teal-600 hover:brightness-110 text-white font-bold py-3 rounded-xl shadow-lg shadow-emerald-500/20 border border-emerald-500/30 transition text-sm flex items-center justify-center gap-2">
                  <HeartHandshake className="w-5 h-5" /> Submit Found Item Report
                </button>
              </form>
            </div>
          </div>
        )}

        {/* PAGE 5: USER DASHBOARD */}
        {activeTab === 'user_dash' && (
          <div className="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div className="flex flex-wrap justify-between items-center gap-4 mb-8">
              <div>
                <h1 className="text-3xl font-extrabold text-white flex items-center gap-2">
                  <LayoutDashboard className="w-8 h-8 text-indigo-400" /> User Dashboard
                </h1>
                <p className="text-slate-400 text-sm mt-1">Welcome back, <strong className="text-white">Rahul Sharma</strong>!</p>
              </div>
              <div className="flex gap-2">
                <button onClick={() => setActiveTab('report_lost')} className="bg-rose-600/90 hover:bg-rose-600 text-white font-semibold text-xs px-4 py-2 rounded-xl shadow-lg border border-rose-500/30">
                  + Report Lost
                </button>
                <button onClick={() => setActiveTab('report_found')} className="bg-emerald-600/90 hover:bg-emerald-600 text-white font-semibold text-xs px-4 py-2 rounded-xl shadow-lg border border-emerald-500/30">
                  + Report Found
                </button>
              </div>
            </div>

            {/* Dashboard Table */}
            <div className="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-xl overflow-hidden">
              <h3 className="text-lg font-bold text-white mb-4">My Submitted Reports</h3>
              <div className="overflow-x-auto">
                <table className="w-full text-left text-sm text-slate-300">
                  <thead className="bg-white/5 text-xs font-bold text-slate-400 uppercase">
                    <tr>
                      <th className="p-3">Type</th>
                      <th className="p-3">Item</th>
                      <th className="p-3">Category</th>
                      <th className="p-3">Date</th>
                      <th className="p-3">Status</th>
                      <th className="p-3 text-right">Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-white/5">
                    {items.map(item => (
                      <tr key={item.id} className="hover:bg-white/5">
                        <td className="p-3">
                          <span className={`px-2.5 py-1 rounded-full text-[10px] font-bold ${item.type === 'lost' ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'}`}>
                            {item.type.toUpperCase()}
                          </span>
                        </td>
                        <td className="p-3 font-semibold text-white">{item.item_name}</td>
                        <td className="p-3 text-slate-400">{categories.find(c => c.id === item.category_id)?.category_name}</td>
                        <td className="p-3 text-xs text-slate-400">{item.date}</td>
                        <td className="p-3">
                          <span className={`px-2.5 py-1 rounded-full text-xs font-semibold ${
                            item.status === 'Approved' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' :
                            item.status === 'Pending' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' :
                            item.status === 'Claimed' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'
                          }`}>
                            {item.status}
                          </span>
                        </td>
                        <td className="p-3 text-right">
                          <div className="flex justify-end gap-2">
                            {item.status !== 'Claimed' && (
                              <button
                                onClick={() => updateItemStatus(item.id, 'Claimed')}
                                className="bg-emerald-600/80 hover:bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1 rounded-lg flex items-center gap-1 border border-emerald-500/30"
                              >
                                <Check className="w-3.5 h-3.5" /> Mark Reunited
                              </button>
                            )}
                            <button
                              onClick={() => deleteItem(item.id)}
                              className="bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 p-1.5 rounded-lg border border-rose-500/30 transition"
                            >
                              <Trash2 className="w-4 h-4" />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}

        {/* PAGE 6: ADMIN DASHBOARD */}
        {activeTab === 'admin_dash' && (
          <div className="max-w-7xl mx-auto px-4 sm:px-6 py-10">
            <div className="mb-8">
              <h1 className="text-3xl font-extrabold text-white flex items-center gap-2">
                <ShieldCheck className="w-8 h-8 text-amber-400" /> Admin Control Center
              </h1>
              <p className="text-slate-400 text-sm mt-1">Manage item approvals, categories, and system moderation</p>
            </div>

            {/* Category Manager Box */}
            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
              <div className="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-xl">
                <h3 className="text-base font-bold text-white mb-3 flex items-center gap-2">
                  <Tag className="w-4 h-4 text-indigo-400" /> Add New Category
                </h3>
                <form onSubmit={handleAddCategory} className="space-y-3">
                  <input
                    type="text"
                    placeholder="e.g. Smartwatches"
                    value={newCatName}
                    onChange={e => setNewCatName(e.target.value)}
                    className="w-full px-3 py-2 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                  />
                  <button type="submit" className="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:brightness-110 text-white font-semibold py-2 rounded-xl text-xs transition border border-indigo-500/30">
                    + Add Category
                  </button>
                </form>
              </div>

              <div className="lg:col-span-2 p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-xl">
                <h3 className="text-base font-bold text-white mb-3">Existing Categories</h3>
                <div className="flex flex-wrap gap-2">
                  {categories.map(c => (
                    <span key={c.id} className="bg-white/5 text-slate-300 text-xs font-semibold px-3 py-1.5 rounded-xl border border-white/10 flex items-center gap-1.5">
                      <Tag className="w-3.5 h-3.5 text-indigo-400" /> {c.category_name}
                    </span>
                  ))}
                </div>
              </div>
            </div>

            {/* Item Approval Workflow Table */}
            <div className="p-6 rounded-2xl bg-white/5 border border-white/10 backdrop-blur-md shadow-xl overflow-hidden">
              <h3 className="text-lg font-bold text-white mb-4">Moderate Submitted Reports</h3>
              <div className="overflow-x-auto">
                <table className="w-full text-left text-sm text-slate-300">
                  <thead className="bg-white/5 text-xs font-bold text-slate-400 uppercase">
                    <tr>
                      <th className="p-3">Type</th>
                      <th className="p-3">Reporter</th>
                      <th className="p-3">Item Name</th>
                      <th className="p-3">Location</th>
                      <th className="p-3">Status</th>
                      <th className="p-3 text-right">Moderation Actions</th>
                    </tr>
                  </thead>
                  <tbody className="divide-y divide-white/5">
                    {items.map(item => (
                      <tr key={item.id} className="hover:bg-white/5">
                        <td className="p-3">
                          <span className={`px-2.5 py-1 rounded-full text-[10px] font-bold ${item.type === 'lost' ? 'bg-rose-500/20 text-rose-400 border border-rose-500/30' : 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30'}`}>
                            {item.type.toUpperCase()}
                          </span>
                        </td>
                        <td className="p-3 font-medium text-slate-300">{item.user_name}</td>
                        <td className="p-3 font-semibold text-white">{item.item_name}</td>
                        <td className="p-3 text-xs text-slate-400">{item.location}</td>
                        <td className="p-3">
                          <span className={`px-2.5 py-1 rounded-full text-xs font-semibold ${
                            item.status === 'Approved' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' :
                            item.status === 'Pending' ? 'bg-amber-500/20 text-amber-300 border border-amber-500/30' :
                            item.status === 'Claimed' ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'bg-rose-500/20 text-rose-300 border border-rose-500/30'
                          }`}>
                            {item.status}
                          </span>
                        </td>
                        <td className="p-3 text-right">
                          <div className="flex justify-end gap-1.5">
                            {item.status !== 'Approved' && (
                              <button
                                onClick={() => updateItemStatus(item.id, 'Approved')}
                                className="bg-emerald-600/80 hover:bg-emerald-600 text-white text-xs font-semibold px-2.5 py-1 rounded-lg border border-emerald-500/30"
                              >
                                Approve
                              </button>
                            )}
                            {item.status !== 'Rejected' && (
                              <button
                                onClick={() => updateItemStatus(item.id, 'Rejected')}
                                className="bg-amber-600/80 hover:bg-amber-600 text-white text-xs font-semibold px-2.5 py-1 rounded-lg border border-amber-500/30"
                              >
                                Reject
                              </button>
                            )}
                            <button
                              onClick={() => deleteItem(item.id)}
                              className="bg-rose-500/20 hover:bg-rose-500/30 text-rose-400 p-1.5 rounded-lg border border-rose-500/30 transition"
                            >
                              <Trash2 className="w-4 h-4" />
                            </button>
                          </div>
                        </td>
                      </tr>
                    ))}
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        )}

        {/* PAGE 7: ABOUT PAGE */}
        {activeTab === 'about' && (
          <div className="max-w-5xl mx-auto px-4 py-12">
            <div className="p-8 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl mb-8">
              <span className="bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 text-xs font-bold px-3 py-1 rounded-full mb-4 inline-block">
                Academic Project Overview
              </span>
              <h1 className="text-3xl font-extrabold text-white mb-4">About Lost & Found Portal</h1>
              <p className="text-slate-300 leading-relaxed mb-6">
                Designed and built as a comprehensive <strong className="text-white">BCA Final Year Mini Project</strong> to solve real-world campus lost item recovery challenges using standard web technologies (Core PHP 8, MySQL, Bootstrap 5).
              </p>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 my-8">
                <div className="p-4 bg-white/5 border border-white/10 rounded-2xl">
                  <ShieldCheck className="w-6 h-6 text-indigo-400 mb-2" />
                  <h3 className="font-bold text-white text-sm">Prepared Statements</h3>
                  <p className="text-slate-400 text-xs mt-1">PDO prepared statement architecture prevents SQL injection vulnerability.</p>
                </div>
                <div className="p-4 bg-white/5 border border-white/10 rounded-2xl">
                  <CheckCircle className="w-6 h-6 text-emerald-400 mb-2" />
                  <h3 className="font-bold text-white text-sm">Role Based Access Control</h3>
                  <p className="text-slate-400 text-xs mt-1">Structured authorization guards for Guests, Users, and Administrators.</p>
                </div>
              </div>
            </div>
          </div>
        )}

        {/* PAGE 8: CONTACT PAGE */}
        {activeTab === 'contact' && (
          <div className="max-w-4xl mx-auto px-4 py-12">
            <div className="p-8 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl">
              <h1 className="text-2xl font-bold text-white mb-4">Contact Campus Helpdesk</h1>
              <p className="text-slate-400 text-sm mb-6">Have questions regarding a lost item or need administrator assistance?</p>
              
              <form onSubmit={e => { e.preventDefault(); showToast('Message sent to helpdesk!'); }} className="space-y-4">
                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Your Name</label>
                  <input required type="text" placeholder="Rahul Sharma" className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Email Address</label>
                  <input required type="email" placeholder="rahul@example.com" className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                <div>
                  <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">Message</label>
                  <textarea required rows={4} placeholder="Your query..." className="w-full px-3.5 py-2.5 bg-slate-900/80 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
                </div>
                <button type="submit" className="bg-gradient-to-r from-indigo-600 to-purple-600 hover:brightness-110 text-white font-bold py-3 px-6 rounded-xl text-sm shadow-lg shadow-indigo-500/20 border border-indigo-500/30 transition">
                  Send Message
                </button>
              </form>
            </div>
          </div>
        )}

        {/* PAGE 9: PROJECT FILES VIEWER */}
        {activeTab === 'php_files' && (
          <div className="max-w-6xl mx-auto px-4 py-10">
            <div className="p-6 sm:p-8 rounded-3xl bg-white/5 border border-white/10 backdrop-blur-md shadow-2xl text-white">
              <div className="flex items-center justify-between mb-6">
                <div>
                  <h1 className="text-2xl font-bold flex items-center gap-2">
                    <FolderOpen className="w-6 h-6 text-indigo-400" /> Complete Project Source Files
                  </h1>
                  <p className="text-slate-400 text-xs sm:text-sm">Generated for XAMPP deployment (`LostAndFoundPortal/` directory)</p>
                </div>
              </div>

              <div className="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs font-mono">
                <div className="bg-slate-900/80 p-4 rounded-xl border border-white/10">
                  <span className="text-emerald-400 font-bold block mb-2">📁 Core Structure</span>
                  <ul className="space-y-1 text-slate-300">
                    <li>• index.php</li>
                    <li>• login.php</li>
                    <li>• register.php</li>
                    <li>• search.php</li>
                    <li>• about.php & contact.php</li>
                  </ul>
                </div>

                <div className="bg-slate-900/80 p-4 rounded-xl border border-white/10">
                  <span className="text-indigo-400 font-bold block mb-2">📁 User & Admin Modules</span>
                  <ul className="space-y-1 text-slate-300">
                    <li>• user/dashboard.php</li>
                    <li>• user/report_lost.php</li>
                    <li>• user/report_found.php</li>
                    <li>• admin/dashboard.php</li>
                    <li>• admin/manage_lost.php</li>
                  </ul>
                </div>

                <div className="bg-slate-900/80 p-4 rounded-xl border border-white/10">
                  <span className="text-amber-400 font-bold block mb-2">📁 Config & Database</span>
                  <ul className="space-y-1 text-slate-300">
                    <li>• config/config.php</li>
                    <li>• database/schema.sql</li>
                    <li>• includes/header.php</li>
                    <li>• assets/css/style.css</li>
                    <li>• README.md</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        )}

      </main>

      {/* DETAIL MODAL */}
      {selectedItem && (
        <div className="fixed inset-0 z-50 bg-black/80 backdrop-blur-md flex items-center justify-center p-4">
          <div className="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl max-w-lg w-full overflow-hidden animate-in fade-in zoom-in duration-200">
            <div className={`p-4 text-white font-bold flex justify-between items-center ${selectedItem.type === 'lost' ? 'bg-rose-600/90 border-b border-rose-500/30' : 'bg-emerald-600/90 border-b border-emerald-500/30'}`}>
              <span className="flex items-center gap-2">
                {selectedItem.type === 'lost' ? <ShieldAlert className="w-5 h-5" /> : <HeartHandshake className="w-5 h-5" />}
                {selectedItem.item_name}
              </span>
              <button onClick={() => setSelectedItem(null)} className="text-white hover:opacity-80 text-xl font-bold">×</button>
            </div>
            
            <div className="p-6">
              <img src={selectedItem.image} alt={selectedItem.item_name} className="w-full h-48 object-cover rounded-2xl mb-4 border border-white/10" />
              
              <div className="space-y-2 text-sm text-slate-300 mb-4">
                <p><strong className="text-white">Category:</strong> {categories.find(c => c.id === selectedItem.category_id)?.category_name}</p>
                <p><strong className="text-white">Date:</strong> {selectedItem.date}</p>
                <p><strong className="text-white">Location:</strong> {selectedItem.location}</p>
                {selectedItem.reward ? <p className="text-amber-300 font-bold">Reward Offered: ₹{selectedItem.reward}</p> : null}
                <p><strong className="text-white">Description:</strong> {selectedItem.description}</p>
              </div>

              {/* Direct QR Code Generator & Poster Share Section */}
              <div className="mb-6 bg-gradient-to-br from-indigo-950/70 via-slate-900 to-purple-950/70 border border-indigo-500/30 p-4 rounded-2xl shadow-xl">
                <div className="flex items-center justify-between mb-3 pb-2 border-b border-white/10">
                  <span className="text-xs font-bold text-indigo-300 uppercase tracking-wider flex items-center gap-1.5">
                    <QrCode className="w-4 h-4 text-indigo-400 animate-pulse" /> Direct Item QR Code
                  </span>
                  <span className="text-[10px] bg-indigo-500/20 text-indigo-300 px-2 py-0.5 rounded-full border border-indigo-500/30 font-mono">
                    ID #{selectedItem.id}
                  </span>
                </div>

                <div className="flex flex-col sm:flex-row items-center gap-4">
                  {/* Scannable SVG QR Code */}
                  <div className="p-2.5 bg-white rounded-xl shadow-lg border border-slate-200 flex-shrink-0 flex flex-col items-center">
                    <QRCodeSVG
                      value={getItemDirectUrl(selectedItem.id)}
                      size={110}
                      level="H"
                      includeMargin={false}
                    />
                    <span className="text-[9px] text-slate-800 font-bold text-center mt-1 font-mono tracking-tighter">
                      SCAN TO VIEW
                    </span>
                  </div>

                  {/* Poster & Direct Link Controls */}
                  <div className="space-y-2 flex-grow text-center sm:text-left">
                    <p className="text-xs text-slate-300 leading-relaxed">
                      Scan with any smartphone camera to open this item detail view directly.
                    </p>

                    <div className="flex flex-wrap gap-2 justify-center sm:justify-start pt-1">
                      <button
                        type="button"
                        onClick={() => setShowPosterModal(selectedItem)}
                        className="bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-bold text-xs px-3.5 py-2 rounded-xl transition border border-indigo-400/30 flex items-center gap-1.5 shadow-md shadow-indigo-500/20"
                      >
                        <Printer className="w-4 h-4 text-indigo-200" /> Print Lost/Found Poster
                      </button>

                      <button
                        type="button"
                        onClick={() => handleCopyLink(selectedItem.id)}
                        className="bg-white/10 hover:bg-white/20 text-slate-200 font-semibold text-xs px-3 py-2 rounded-xl transition border border-white/10 flex items-center gap-1.5"
                      >
                        {copiedLinkId === selectedItem.id ? (
                          <>
                            <Check className="w-3.5 h-3.5 text-emerald-400" /> Link Copied!
                          </>
                        ) : (
                          <>
                            <Copy className="w-3.5 h-3.5 text-indigo-300" /> Copy QR Link
                          </>
                        )}
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              {/* Tamil Nadu Map Preview */}
              <div className="mb-6 rounded-2xl overflow-hidden border border-white/10 bg-slate-950">
                <div className="p-2.5 bg-white/5 border-b border-white/10 flex items-center justify-between text-xs text-slate-300">
                  <span className="flex items-center gap-1.5 font-bold text-white">
                    <MapPin className="w-3.5 h-3.5 text-indigo-400" /> Tamil Nadu Location Map
                  </span>
                  <a
                    href={`https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(selectedItem.location)}`}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-indigo-400 hover:text-indigo-300 underline font-medium text-[11px] flex items-center gap-1"
                  >
                    Open Google Maps <ArrowRight className="w-3 h-3" />
                  </a>
                </div>
                <div className="h-44 w-full relative">
                  <iframe
                    title="Tamil Nadu Item Location Map"
                    width="100%"
                    height="100%"
                    style={{ border: 0 }}
                    loading="lazy"
                    allowFullScreen
                    src={`https://maps.google.com/maps?q=${encodeURIComponent(selectedItem.location + ', Tamil Nadu, India')}&t=&z=13&ie=UTF8&iwloc=&output=embed`}
                  ></iframe>
                </div>
              </div>

              <div className="bg-white/5 border border-white/10 p-4 rounded-2xl text-center">
                <span className="text-xs text-slate-400 block mb-1">Contact Phone Number:</span>
                <a href={`tel:${selectedItem.contact}`} className="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-6 py-2.5 rounded-xl text-sm inline-flex items-center gap-2 shadow-lg shadow-emerald-500/20 border border-emerald-500/30 transition">
                  <Phone className="w-4 h-4" /> Call {selectedItem.contact}
                </a>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* PRINTABLE LOST / FOUND POSTER MODAL */}
      {showPosterModal && (
        <div className="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
          <div className="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl max-w-2xl w-full flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
            {/* Modal Top Controls Header (Hidden in Print) */}
            <div className="p-4 bg-slate-950 border-b border-white/10 flex flex-wrap justify-between items-center gap-3 text-white">
              <div className="flex items-center gap-2">
                <Printer className="w-5 h-5 text-indigo-400" />
                <div>
                  <h3 className="font-bold text-sm sm:text-base leading-none">Official Printable Poster</h3>
                  <span className="text-[11px] text-slate-400">Print or save as PDF to post on campus noticeboards</span>
                </div>
              </div>
              <div className="flex items-center gap-2">
                <button
                  type="button"
                  onClick={handlePrintPoster}
                  className="bg-gradient-to-r from-emerald-600 to-teal-600 hover:brightness-110 text-white font-bold text-xs px-4 py-2 rounded-xl shadow-lg border border-emerald-400/30 flex items-center gap-1.5 transition"
                >
                  <Printer className="w-4 h-4" /> Print / Save as PDF
                </button>
                <button
                  type="button"
                  onClick={() => handleCopyLink(showPosterModal.id)}
                  className="bg-white/10 hover:bg-white/20 text-slate-200 text-xs font-semibold px-3 py-2 rounded-xl border border-white/10 flex items-center gap-1 transition"
                >
                  {copiedLinkId === showPosterModal.id ? <Check className="w-3.5 h-3.5 text-emerald-400" /> : <Copy className="w-3.5 h-3.5 text-indigo-300" />}
                  Copy Link
                </button>
                <button
                  type="button"
                  onClick={() => setShowPosterModal(null)}
                  className="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-white/10 transition"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>
            </div>

            {/* Printable Poster Canvas Area */}
            <div className="p-4 sm:p-8 bg-slate-950 overflow-y-auto max-h-[80vh]">
              <div
                id="printable-poster-area"
                className="bg-white text-slate-900 p-6 sm:p-8 rounded-2xl shadow-2xl border-4 border-slate-900 max-w-xl mx-auto font-sans"
              >
                {/* Header Banner */}
                <div className={`p-4 rounded-xl text-center text-white mb-6 ${showPosterModal.type === 'lost' ? 'bg-rose-600' : 'bg-emerald-600'}`}>
                  <h1 className="text-2xl sm:text-3xl font-black uppercase tracking-wider">
                    {showPosterModal.type === 'lost' ? '🚨 LOST ITEM NOTICE' : '📢 FOUND ITEM NOTICE'}
                  </h1>
                  {showPosterModal.reward ? (
                    <div className="mt-2 inline-block bg-amber-300 text-slate-950 font-black text-sm sm:text-base px-4 py-1 rounded-full uppercase shadow">
                      REWARD OFFERED: ₹{showPosterModal.reward}
                    </div>
                  ) : (
                    <p className="text-xs sm:text-sm font-semibold opacity-90 mt-1">
                      {showPosterModal.type === 'lost' ? 'PLEASE HELP US LOCATE THIS MISPLACED BELONGING' : 'HELP REUNITE THIS ITEM WITH ITS RIGHTFUL OWNER'}
                    </p>
                  )}
                </div>

                {/* Item Photo & Title */}
                <div className="text-center mb-6">
                  <div className="w-full h-56 bg-slate-100 rounded-xl overflow-hidden border-2 border-slate-300 mb-4 flex items-center justify-center">
                    <img src={showPosterModal.image} alt={showPosterModal.item_name} className="w-full h-full object-cover" />
                  </div>
                  <h2 className="text-xl sm:text-2xl font-black text-slate-900 leading-tight mb-2">
                    {showPosterModal.item_name}
                  </h2>
                  <div className="inline-flex items-center gap-2 bg-slate-100 px-3 py-1 rounded-full text-xs font-bold text-slate-700 border border-slate-300">
                    Category: {categories.find(c => c.id === showPosterModal.category_id)?.category_name}
                  </div>
                </div>

                {/* Details Grid */}
                <div className="space-y-3 bg-slate-50 p-4 rounded-xl border border-slate-200 mb-6 text-sm text-slate-800">
                  <div>
                    <span className="font-bold text-slate-900 uppercase text-xs block text-slate-500">Date Reported:</span>
                    <p className="font-medium">{showPosterModal.date}</p>
                  </div>
                  <div>
                    <span className="font-bold text-slate-900 uppercase text-xs block text-slate-500">Location Details:</span>
                    <p className="font-bold text-indigo-950">{showPosterModal.location}</p>
                  </div>
                  <div>
                    <span className="font-bold text-slate-900 uppercase text-xs block text-slate-500">Description:</span>
                    <p className="text-xs sm:text-sm leading-relaxed">{showPosterModal.description}</p>
                  </div>
                </div>

                {/* Contact Box */}
                <div className="bg-slate-900 text-white p-4 rounded-xl text-center mb-6">
                  <span className="text-xs text-slate-300 block uppercase font-bold tracking-wider mb-1">
                    If Found / Have Information, Contact Immediately:
                  </span>
                  <span className="text-xl sm:text-2xl font-black text-amber-300 tracking-widest block font-mono">
                    📞 {showPosterModal.contact}
                  </span>
                </div>

                {/* QR CODE SECTION */}
                <div className="border-2 border-dashed border-slate-400 p-4 rounded-2xl flex flex-col sm:flex-row items-center gap-4 bg-slate-50 text-center sm:text-left">
                  <div className="p-3 bg-white border border-slate-300 rounded-xl shadow flex-shrink-0">
                    <QRCodeSVG
                      value={getItemDirectUrl(showPosterModal.id)}
                      size={120}
                      level="H"
                      includeMargin={false}
                    />
                  </div>
                  <div className="space-y-1">
                    <span className="text-xs font-black text-indigo-800 uppercase tracking-wider block flex items-center gap-1 justify-center sm:justify-start">
                      <QrCode className="w-4 h-4 text-indigo-600" /> SCAN QR CODE TO CLAIM ONLINE
                    </span>
                    <p className="text-xs text-slate-600 leading-snug">
                      Point your mobile camera at this QR code to view live status, photos, and submit recovery/claim requests online.
                    </p>
                    <p className="text-[10px] text-slate-400 font-mono truncate max-w-xs mt-1">
                      {getItemDirectUrl(showPosterModal.id)}
                    </p>
                  </div>
                </div>

                {/* Poster Footer */}
                <div className="mt-6 pt-4 border-t border-slate-200 text-center text-[10px] text-slate-400 uppercase tracking-widest font-bold">
                  Campus Lost & Found Online Portal • Tamil Nadu System
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* PRINT MEDIA STYLES */}
      <style>{`
        @media print {
          body * {
            visibility: hidden !important;
          }
          #printable-poster-area, #printable-poster-area * {
            visibility: visible !important;
          }
          #printable-poster-area {
            position: fixed !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            height: auto !important;
            margin: 0 !important;
            padding: 24px !important;
            box-shadow: none !important;
            border: 3px solid #000 !important;
            background: white !important;
            color: black !important;
            z-index: 99999 !important;
          }
        }
      `}</style>

      {/* TAMIL NADU INTERACTIVE MAP MODAL */}
      {showTNMapModal && (
        <div className="fixed inset-0 z-50 bg-black/85 backdrop-blur-md flex items-center justify-center p-3 sm:p-6 overflow-y-auto">
          <div className="bg-slate-900 border border-white/10 rounded-3xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in duration-200">
            {/* Modal Header */}
            <div className="p-4 bg-gradient-to-r from-indigo-900/90 via-purple-900/90 to-slate-900 border-b border-white/10 flex justify-between items-center text-white">
              <div className="flex items-center gap-3">
                <div className="p-2 rounded-xl bg-indigo-500/20 border border-indigo-500/30 text-indigo-300">
                  <Map className="w-6 h-6" />
                </div>
                <div>
                  <h2 className="text-lg font-bold">Tamil Nadu Map Location Picker</h2>
                  <p className="text-xs text-slate-300">Select campus hotspots, districts & specific spots across Tamil Nadu</p>
                </div>
              </div>
              <button
                onClick={() => setShowTNMapModal(false)}
                className="text-slate-400 hover:text-white p-2 rounded-xl hover:bg-white/10 transition"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            {/* Modal Body */}
            <div className="p-4 sm:p-6 overflow-y-auto space-y-6 flex-1 text-slate-200">
              {/* District Filter Selector */}
              <div>
                <label className="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 flex items-center gap-1.5">
                  <Globe className="w-4 h-4 text-indigo-400" /> Select District / City in Tamil Nadu
                </label>
                <div className="flex flex-wrap gap-2 max-h-24 overflow-y-auto pr-1 custom-scrollbar">
                  {TAMILNADU_DISTRICTS.filter(d => d !== 'All Tamil Nadu').map(district => (
                    <button
                      key={district}
                      onClick={() => {
                        setActiveMapDistrict(district);
                        const match = TAMILNADU_HOTSPOTS.find(h => h.district === district);
                        setSelectedMapHotspot(match || null);
                        setMapPinPulseKey(k => k + 1);
                      }}
                      className={`text-xs font-semibold px-3 py-1.5 rounded-xl border transition ${
                        activeMapDistrict === district
                          ? 'bg-indigo-600 text-white border-indigo-400 shadow-md shadow-indigo-500/30'
                          : 'bg-white/5 hover:bg-white/10 text-slate-300 border-white/10'
                      }`}
                    >
                      📍 {district}
                    </button>
                  ))}
                </div>
              </div>

              <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
                {/* Left Side: Campus & Landmark Hotspots */}
                <div className="space-y-4">
                  <div className="flex items-center justify-between">
                    <h3 className="text-sm font-bold text-white flex items-center gap-1.5">
                      <Compass className="w-4 h-4 text-indigo-400" /> Hotspots in {activeMapDistrict}
                    </h3>
                    <span className="text-[11px] text-slate-400">Click to select spot</span>
                  </div>

                  <div className="space-y-3 max-h-72 overflow-y-auto pr-1">
                    {TAMILNADU_HOTSPOTS.filter(h => h.district === activeMapDistrict).length > 0 ? (
                      TAMILNADU_HOTSPOTS.filter(h => h.district === activeMapDistrict).map(hotspot => (
                        <div
                          key={hotspot.id}
                          onClick={() => {
                            setSelectedMapHotspot(hotspot);
                            setMapPinPulseKey(k => k + 1);
                          }}
                          className={`p-3.5 rounded-2xl border cursor-pointer transition-all ${
                            selectedMapHotspot?.id === hotspot.id
                              ? 'bg-indigo-900/40 border-indigo-500 shadow-lg shadow-indigo-500/10'
                              : 'bg-white/5 hover:bg-white/10 border-white/10'
                          }`}
                        >
                          <div className="flex items-start justify-between mb-1.5">
                            <span className="font-bold text-white text-sm">{hotspot.name}</span>
                            <span className="text-[10px] bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 px-2 py-0.5 rounded-full font-semibold">
                              {hotspot.category}
                            </span>
                          </div>
                          <p className="text-xs text-slate-400 mb-1">District: {hotspot.district}, Tamil Nadu</p>
                          <p className="text-[10px] text-indigo-300 font-mono mb-2">GPS: {hotspot.coords.lat}° N, {hotspot.coords.lng}° E</p>
                          
                          <div className="space-y-1">
                            <span className="text-[10px] uppercase font-bold text-slate-400 block">Popular Spots Inside Campus:</span>
                            <div className="flex flex-wrap gap-1">
                              {hotspot.popularSpots.map(spot => (
                                <button
                                  key={spot}
                                  onClick={(e) => {
                                    e.stopPropagation();
                                    setSelectedMapHotspot(hotspot);
                                    setCustomSpotDetail(spot);
                                    setMapPinPulseKey(k => k + 1);
                                  }}
                                  className={`text-[11px] px-2 py-0.5 rounded-md border transition ${
                                    customSpotDetail === spot && selectedMapHotspot?.id === hotspot.id
                                      ? 'bg-emerald-600 text-white border-emerald-400 font-bold'
                                      : 'bg-slate-800 text-slate-300 hover:text-white border-white/10'
                                  }`}
                                >
                                  + {spot}
                                </button>
                              ))}
                            </div>
                          </div>
                        </div>
                      ))
                    ) : (
                      <div className="p-6 bg-white/5 border border-white/10 rounded-2xl text-center text-slate-400 text-xs">
                        No specific college hotspots listed for {activeMapDistrict} yet. You can use district-level location selection!
                      </div>
                    )}
                  </div>

                  {/* Custom Landmark Detail Field */}
                  <div className="pt-2">
                    <label className="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-1">
                      Specific Room / Building / Area (Optional)
                    </label>
                    <input
                      type="text"
                      placeholder="e.g. 2nd Floor Room 102, Near Water Cooler..."
                      value={customSpotDetail}
                      onChange={e => {
                        setCustomSpotDetail(e.target.value);
                      }}
                      className="w-full px-3.5 py-2.5 bg-slate-950 border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                    />
                  </div>
                </div>

                {/* Right Side: Map Embed Preview & Dynamic Visual Pin Overlay */}
                <div className="space-y-4 flex flex-col justify-between">
                  <div className="space-y-2">
                    <div className="flex items-center justify-between gap-2">
                      <span className="text-xs font-bold text-slate-300 uppercase tracking-wider flex items-center gap-1.5">
                        <Navigation className="w-4 h-4 text-emerald-400" /> Dynamic Map Preview (Tamil Nadu)
                      </span>
                      <div className="flex items-center gap-1.5">
                        <button
                          type="button"
                          onClick={() => setMapViewType(v => v === 'm' ? 'k' : 'm')}
                          className={`px-2 py-1 text-[11px] font-semibold rounded-lg border transition flex items-center gap-1 ${
                            mapViewType === 'k' ? 'bg-amber-500/20 text-amber-300 border-amber-500/30' : 'bg-white/5 text-slate-300 border-white/10'
                          }`}
                          title="Toggle Satellite View"
                        >
                          <Layers className="w-3 h-3" /> {mapViewType === 'k' ? 'Satellite' : 'Roadmap'}
                        </button>
                        <button
                          type="button"
                          onClick={() => setMapZoom(z => Math.min(18, z + 1))}
                          className="p-1 bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 rounded-lg transition"
                          title="Zoom In"
                        >
                          <ZoomIn className="w-3.5 h-3.5" />
                        </button>
                        <button
                          type="button"
                          onClick={() => setMapZoom(z => Math.max(9, z - 1))}
                          className="p-1 bg-white/5 hover:bg-white/10 text-slate-300 border border-white/10 rounded-lg transition"
                          title="Zoom Out"
                        >
                          <ZoomOut className="w-3.5 h-3.5" />
                        </button>
                        <button
                          type="button"
                          onClick={() => setShowPinTooltip(s => !s)}
                          className={`p-1 border rounded-lg transition ${
                            showPinTooltip ? 'bg-rose-500/20 text-rose-300 border-rose-500/30' : 'bg-white/5 text-slate-400 border-white/10'
                          }`}
                          title="Toggle Pin Details Overlay"
                        >
                          <Eye className="w-3.5 h-3.5" />
                        </button>
                      </div>
                    </div>
                    
                    <div className="h-64 sm:h-72 rounded-2xl overflow-hidden border border-white/10 bg-slate-950 relative group">
                      <iframe
                        title="Selected Tamil Nadu Location Map"
                        width="100%"
                        height="100%"
                        style={{ border: 0 }}
                        loading="lazy"
                        allowFullScreen
                        src={`https://maps.google.com/maps?q=${encodeURIComponent(
                          (selectedMapHotspot ? selectedMapHotspot.name + ', ' + selectedMapHotspot.district : activeMapDistrict) + ', Tamil Nadu, India'
                        )}&t=${mapViewType}&z=${mapZoom}&ie=UTF8&iwloc=&output=embed`}
                      ></iframe>

                      {/* DYNAMIC VISUAL PIN OVERLAY */}
                      <div className="absolute inset-0 flex flex-col items-center justify-center pointer-events-none z-10 p-2">
                        {showPinTooltip && (
                          <div className="pointer-events-auto mb-2 bg-slate-950/90 border border-rose-500/50 shadow-2xl backdrop-blur-md px-3.5 py-2 rounded-2xl text-center max-w-[270px] transform hover:scale-105 transition duration-300 animate-in fade-in slide-in-from-top-2">
                            <div className="flex items-center justify-center gap-1.5 mb-0.5">
                              <span className="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                              <span className="text-[10px] font-extrabold uppercase tracking-widest text-rose-300">
                                LIVE LOCATION PIN
                              </span>
                            </div>
                            <p className="text-xs font-bold text-white truncate">
                              📍 {selectedMapHotspot ? selectedMapHotspot.name : activeMapDistrict}
                            </p>
                            <p className="text-[11px] text-slate-300 truncate mt-0.5">
                              {customSpotDetail ? customSpotDetail : (selectedMapHotspot?.category || 'Tamil Nadu District')}
                            </p>
                            <div className="mt-1 pt-1 border-t border-white/10 flex items-center justify-between text-[10px] text-slate-400">
                              <span>GPS: {selectedMapHotspot ? `${selectedMapHotspot.coords.lat}° N, ${selectedMapHotspot.coords.lng}° E` : 'Tamil Nadu'}</span>
                              <span className="text-emerald-400 font-semibold">Active Pin</span>
                            </div>
                          </div>
                        )}

                        {/* VISUAL BOUNCING PIN & RADAR RING */}
                        <div
                          className="relative flex flex-col items-center justify-center cursor-pointer pointer-events-auto"
                          onClick={() => setMapPinPulseKey(k => k + 1)}
                          title="Click to Pulse Location Pin"
                        >
                          {/* Radar Pulse Effect */}
                          <div key={mapPinPulseKey} className="w-12 h-12 rounded-full bg-rose-500/40 border-2 border-rose-400 animate-ping absolute -bottom-1"></div>
                          
                          {/* Pin Graphic */}
                          <div className="text-rose-500 drop-shadow-[0_6px_16px_rgba(244,63,94,0.9)] transform -translate-y-1 hover:scale-110 transition duration-200 animate-bounce">
                            <MapPin className="w-10 h-10 fill-rose-500/40 stroke-[2.5]" />
                          </div>
                          
                          {/* Base Glow */}
                          <div className="w-5 h-5 rounded-full bg-rose-600 shadow-[0_0_20px_#f43f5e] border-2 border-white -mt-2"></div>
                          <div className="w-8 h-2 rounded-full bg-black/60 blur-xs -mt-0.5"></div>
                        </div>
                      </div>

                      {/* Floating Recenter & Pulse Button */}
                      <button
                        type="button"
                        onClick={() => setMapPinPulseKey(k => k + 1)}
                        className="absolute bottom-3 right-3 z-20 bg-slate-900/90 hover:bg-slate-800 text-rose-300 border border-rose-500/40 text-[11px] font-semibold px-2.5 py-1.5 rounded-xl shadow-lg transition flex items-center gap-1.5"
                      >
                        <Crosshair className="w-3.5 h-3.5 text-rose-400 animate-spin" style={{ animationDuration: '6s' }} /> Pulse Location Pin
                      </button>
                    </div>
                  </div>

                  {/* Formatted Location Result */}
                  <div className="p-4 bg-indigo-950/60 border border-indigo-500/30 rounded-2xl">
                    <span className="text-[10px] text-indigo-300 uppercase font-bold tracking-wider block mb-1">
                      Selected Location String:
                    </span>
                    <p className="text-sm font-bold text-white flex items-center gap-1.5">
                      <MapPin className="w-4 h-4 text-rose-400 shrink-0" />
                      {customSpotDetail ? `${customSpotDetail}, ` : ''}
                      {selectedMapHotspot ? `${selectedMapHotspot.name}, ${selectedMapHotspot.district}` : activeMapDistrict}, Tamil Nadu
                    </p>
                  </div>

                  {/* Confirm Action Button */}
                  <button
                    onClick={() => {
                      const finalLoc = `${customSpotDetail ? customSpotDetail + ', ' : ''}${selectedMapHotspot ? selectedMapHotspot.name + ', ' + selectedMapHotspot.district : activeMapDistrict}, Tamil Nadu`;
                      applyTNLocation(finalLoc);
                    }}
                    className="w-full bg-gradient-to-r from-emerald-600 via-teal-600 to-indigo-600 hover:brightness-110 text-white font-bold py-3 px-6 rounded-xl text-sm shadow-xl shadow-emerald-500/20 border border-emerald-400/30 transition flex items-center justify-center gap-2"
                  >
                    <CheckCircle className="w-5 h-5" /> Confirm Location for Report
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      )}

      {/* FOOTER */}
      <footer className="bg-black/40 backdrop-blur-xl border-t border-white/10 text-slate-400 text-xs py-8 mt-auto">
        <div className="max-w-7xl mx-auto px-4 text-center sm:text-left flex flex-col sm:flex-row justify-between items-center gap-4">
          <div className="flex items-center gap-3">
            <img src={portalLogo} alt="Immortal Find Logo" className="w-8 h-8 rounded-lg border border-white/20 object-cover shadow-md shadow-indigo-500/20" referrerPolicy="no-referrer" />
            <div>
              <p className="font-bold text-white text-sm">Immortal Find Portal</p>
              <p className="text-slate-500 mt-0.5">BCA Final Year Mini Project • Built with PHP 8, MySQL & Bootstrap 5</p>
            </div>
          </div>
          <div className="flex gap-4">
            <button onClick={() => setActiveTab('about')} className="hover:text-white transition">About Project</button>
            <button onClick={() => setActiveTab('contact')} className="hover:text-white transition">Helpdesk</button>
            <button onClick={() => setActiveTab('php_files')} className="hover:text-white transition">View PHP Source Code</button>
          </div>
        </div>
      </footer>

    </div>
  );
}
