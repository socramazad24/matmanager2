# Estructura Visual del Frontend

## Árbol de Componentes

```
App (BrowserRouter)
├── ThemeProvider
│   └── AuthProvider
│       └── Routes
│           ├── /login
│           │   └── Login
│           │       ├── Input (username)
│           │       ├── Input (password)
│           │       └── Button (submit)
│           │
│           └── / (Protected)
│               └── Layout
│                   ├── Sidebar
│                   │   ├── Logo
│                   │   ├── Nav Items (NavLink)
│                   │   │   ├── Dashboard
│                   │   │   ├── Materials
│                   │   │   ├── Orders
│                   │   │   ├── Providers
│                   │   │   └── Users (admin only)
│                   │   ├── User Info
│                   │   └── Logout Button
│                   │
│                   ├── Header
│                   │   ├── Menu Button (mobile)
│                   │   ├── Title
│                   │   └── Theme Toggle
│                   │
│                   └── Main Content (Outlet)
│                       ├── /dashboard
│                       │   └── Dashboard
│                       │       └── Stats Cards (x4)
│                       │
│                       ├── /materials
│                       │   └── Materials
│                       │       ├── Header + Export Buttons
│                       │       ├── Table
│                       │       │   ├── Headers
│                       │       │   └── Rows
│                       │       │       ├── Data
│                       │       │       └── Actions (Edit/Delete)
│                       │       └── Modal
│                       │           └── Form
│                       │               ├── Inputs (x7)
│                       │               └── Buttons
│                       │
│                       ├── /orders
│                       │   └── Orders
│                       │       ├── Header + Export Buttons
│                       │       ├── Table (with status badges)
│                       │       └── Modal (create only)
│                       │
│                       ├── /providers
│                       │   └── Providers
│                       │       ├── Header + Export Buttons
│                       │       ├── Table
│                       │       └── Modal
│                       │
│                       └── /users (admin)
│                           └── Users
│                               ├── Header + Export Buttons
│                               ├── Table (with role badges)
│                               └── Modal
│                                   └── Form (with select)
```

## Layout Desktop

```
┌─────────────────────────────────────────────────────────────┐
│  Header                                          🌙 Theme    │
├──────────┬──────────────────────────────────────────────────┤
│          │                                                   │
│ Sidebar  │  Main Content Area                               │
│          │                                                   │
│ 📊 Dash  │  ┌────────┐ ┌────────┐ ┌────────┐ ┌────────┐   │
│ 📦 Mats  │  │  Card  │ │  Card  │ │  Card  │ │  Card  │   │
│ 🛒 Ords  │  │  Icon  │ │  Icon  │ │  Icon  │ │  Icon  │   │
│ 🚚 Provs │  │ Value  │ │ Value  │ │ Value  │ │ Value  │   │
│ 👥 Users │  └────────┘ └────────┘ └────────┘ └────────┘   │
│          │                                                   │
│          │  ┌──────────────────────────────────────────┐   │
│          │  │  Table                                   │   │
│  User    │  │  ┌────────────────────────────────────┐ │   │
│  Info    │  │  │ Header Row                         │ │   │
│          │  │  ├────────────────────────────────────┤ │   │
│  Logout  │  │  │ Data Row 1                         │ │   │
│          │  │  │ Data Row 2                         │ │   │
│          │  │  │ Data Row 3                         │ │   │
└──────────┴──┴──└────────────────────────────────────┘─┴───┘
```

## Layout Mobile

```
┌─────────────────────────┐
│ ☰  Header      🌙       │
├─────────────────────────┤
│                         │
│  Main Content           │
│  (Full Width)           │
│                         │
│  ┌─────────────────┐   │
│  │ Card 1          │   │
│  └─────────────────┘   │
│  ┌─────────────────┐   │
│  │ Card 2          │   │
│  └─────────────────┘   │
│                         │
│  ┌─────────────────┐   │
│  │ Table (scroll→) │   │
│  └─────────────────┘   │
│                         │
└─────────────────────────┘

(Sidebar oculto, aparece
 como overlay al tocar ☰)
```

## Modal / Dialog

```
     Background Overlay (50% opacity)
┌─────────────────────────────────────┐
│                                     │
│   ┌─────────────────────────┐      │
│   │ Title               ✕   │      │
│   ├─────────────────────────┤      │
│   │                         │      │
│   │  Form Fields            │      │
│   │  ┌───────────────────┐  │      │
│   │  │ Input 1           │  │      │
│   │  └───────────────────┘  │      │
│   │  ┌───────────────────┐  │      │
│   │  │ Input 2           │  │      │
│   │  └───────────────────┘  │      │
│   │                         │      │
│   │    [Cancel] [Submit]    │      │
│   └─────────────────────────┘      │
│                                     │
└─────────────────────────────────────┘
```

## Tabla con Acciones

```
┌──────────────────────────────────────────────────────────┐
│ ID     │ Name     │ Description │ Price    │ Actions   │
├──────────────────────────────────────────────────────────┤
│ MAT001 │ Cemento  │ Bolsa 50kg  │ $30,000  │ ✏️  🗑️    │
│ MAT002 │ Arena    │ Metro cúbico│ $45,000  │ ✏️  🗑️    │
│ MAT003 │ Varilla  │ 6 metros    │ $15,000  │ ✏️  🗑️    │
└──────────────────────────────────────────────────────────┘
```

## Botones de Exportación

```
┌──────────────────────────────────────────────┐
│  Materials                                   │
│  Gestión de materiales                       │
│                                              │
│  [📥 PDF] [📥 Excel] [➕ Nuevo Material]    │
└──────────────────────────────────────────────┘
```

## Card de Estadística

```
┌──────────────────────┐
│ Materials            │
│                   📦 │
│ 45                   │
│                      │
└──────────────────────┘
```

## Login Page

```
┌─────────────────────────────────┐
│                                 │
│        ┌─────────┐              │
│        │    📦   │              │
│        └─────────┘              │
│       MatManager                │
│                                 │
│  ┌───────────────────────────┐ │
│  │ Usuario                   │ │
│  │ ┌───────────────────────┐ │ │
│  │ │                       │ │ │
│  │ └───────────────────────┘ │ │
│  │                           │ │
│  │ Contraseña                │ │
│  │ ┌───────────────────────┐ │ │
│  │ │                       │ │ │
│  │ └───────────────────────┘ │ │
│  │                           │ │
│  │    [Iniciar Sesión]       │ │
│  └───────────────────────────┘ │
│                                 │
└─────────────────────────────────┘
```

## Sidebar Estados

### Collapsed (Mobile)
```
Hidden offscreen
```

### Open (Mobile)
```
┌──────────────────┐
│ MatManager   ✕   │
├──────────────────┤
│ 📊 Dashboard     │
│ 📦 Materiales    │
│ 🛒 Pedidos       │
│ 🚚 Proveedores   │
│ 👥 Usuarios      │
│                  │
├──────────────────┤
│ User: marcos     │
│ Role: admin      │
│ 🚪 Logout        │
└──────────────────┘
```

### Fixed (Desktop)
```
Siempre visible
```

## Theme Toggle Animation

```
Light Mode:  ☀️  →  🌙
Dark Mode:   🌙  →  ☀️

(Smooth transition 300ms)
```

## Estados de Pedidos

```
┌──────────────────┐  ┌──────────────────┐
│ Pendiente        │  │ Recibido         │
│ (Yellow badge)   │  │ (Green badge)    │
└──────────────────┘  └──────────────────┘
```

## Roles de Usuario

```
┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐
│ Administrador    │  │ Gerente          │  │ Bodeguero        │
│ (Full access)    │  │ (No users)       │  │ (No users)       │
└──────────────────┘  └──────────────────┘  └──────────────────┘
```

## Flujo de Navegación

```
Login
  ↓
Dashboard
  ├→ Materiales
  │   ├→ Ver lista
  │   ├→ Crear nuevo
  │   ├→ Editar
  │   ├→ Eliminar
  │   └→ Exportar (PDF/Excel)
  │
  ├→ Pedidos
  │   ├→ Ver lista
  │   ├→ Crear nuevo
  │   └→ Exportar (PDF/Excel)
  │
  ├→ Proveedores
  │   ├→ Ver lista
  │   ├→ Crear nuevo
  │   └→ Exportar (PDF/Excel)
  │
  └→ Usuarios (Admin only)
      ├→ Ver lista
      ├→ Crear nuevo
      ├→ Editar
      ├→ Eliminar
      └→ Exportar (PDF/Excel)
```

## Color Schemes

### Light Theme
```
┌─────────────────────────┐
│ Background: #FFFFFF     │
│ Text: #1F2937           │
│ Primary: #FCD34D        │
│ Border: #E5E7EB         │
└─────────────────────────┘
```

### Dark Theme
```
┌─────────────────────────┐
│ Background: #1F2937     │
│ Text: #FFFFFF           │
│ Primary: #FCD34D        │
│ Border: #374151         │
└─────────────────────────┘
```

## Iconos Utilizados

```
📊 Dashboard    - LayoutDashboard
📦 Materiales   - Package
🛒 Pedidos      - ShoppingCart
🚚 Proveedores  - Truck
👥 Usuarios     - Users
🚪 Logout       - LogOut
☰  Menu         - Menu
🌙 Dark Mode    - Moon
☀️ Light Mode   - Sun
➕ Add          - Plus
✏️ Edit         - Edit
🗑️ Delete       - Trash2
📥 Download     - FileDown
✕  Close        - X
📈 Stats        - TrendingUp
```

## Breakpoints Responsive

```
Mobile:   < 1024px   (lg breakpoint)
Tablet:   1024px - 1279px
Desktop:  ≥ 1280px

Sidebar behavior:
- Mobile: Hidden by default, overlay when open
- Tablet+: Always visible, fixed position
```

---

Estructura visual del frontend MatManager
Desarrollado con React, TypeScript y TailwindCSS
