// ============================================================
// DAIRY MANAGEMENT SYSTEM - React Single Page Application
// ASSIGNMENT 8 : React Components and JSX
//
// Demonstrates:
// - ReactJS
// - JSX (elements, expressions, className, JavaScript values in JSX)
// - Functional components (no hooks/props/events as per Assignment 9)
// - Component-based UI
// - Basic React rendering
// ============================================================

import './App.css'

// ============================================================
// Component: Header
// Renders the site title, tagline and navigation
// ============================================================
function Header() {
  const siteTitle = "Dairy Management System"
  const tagline = "Your daily partner for milk collection, fat & SNF tracking and farmer payments."

  return (
    <header style={{
      backgroundColor: '#e8b84b',
      textAlign: 'center',
      padding: '25px 10px',
      borderBottom: '4px solid #c98a2d'
    }}>
      <h1 style={{
        margin: 0,
        color: '#4b2e0e',
        fontSize: '34px'
      }}>
        {siteTitle}
      </h1>
      <p style={{
        margin: '8px 0 0 0',
        color: '#5c3a12',
        fontSize: '16px'
      }}>
        {tagline}
      </p>
    </header>
  )
}

// ============================================================
// Component: Dashboard
// Renders overview cards for the Dairy Management System
// ============================================================
function Dashboard() {
  const totalFarmers = 125
  const milkCollectedToday = 1050
  const averageFat = 4.2
  const paymentsPending = 52400

  return (
    <section>
      <h2 style={{
        color: '#7a4a21',
        borderBottom: '2px solid #d9c4a3',
        paddingBottom: '6px'
      }}>
        Today's Overview
      </h2>
      <div className="cards">
        <article style={{
          backgroundColor: '#fdf8ee',
          border: '1px solid #ddc9a5',
          borderLeft: '6px solid #e8b84b',
          borderRadius: '8px',
          padding: '12px 18px',
          margin: '12px 0'
        }}>
          <h3 style={{ margin: '0 0 6px 0', color: '#6b4226' }}>
            Total Farmers
          </h3>
          <p style={{ margin: 0, color: '#5b4a36' }}>
            {totalFarmers} registered milk producers in the village area.
          </p>
        </article>
        <article style={{
          backgroundColor: '#fdf8ee',
          border: '1px solid #ddc9a5',
          borderLeft: '6px solid #e8b84b',
          borderRadius: '8px',
          padding: '12px 18px',
          margin: '12px 0'
        }}>
          <h3 style={{ margin: '0 0 6px 0', color: '#6b4226' }}>
            Milk Collected Today
          </h3>
          <p style={{ margin: 0, color: '#5b4a36' }}>
            {milkCollectedToday} litres from 98 farmers.
          </p>
        </article>
        <article style={{
          backgroundColor: '#fdf8ee',
          border: '1px solid #ddc9a5',
          borderLeft: '6px solid #e8b84b',
          borderRadius: '8px',
          padding: '12px 18px',
          margin: '12px 0'
        }}>
          <h3 style={{ margin: '0 0 6px 0', color: '#6b4226' }}>
            Average Fat
          </h3>
          <p style={{ margin: 0, color: '#5b4a36' }}>
            {averageFat}% average fat content this week.
          </p>
        </article>
        <article style={{
          backgroundColor: '#fde8d7',
          border: '2px solid #d98c5f',
          borderLeft: '6px solid #d98c5f',
          borderRadius: '8px',
          padding: '12px 18px',
          margin: '12px 0'
        }}>
          <h3 style={{ margin: '0 0 6px 0', color: '#6b4226' }}>
            Payments Pending
          </h3>
          <p style={{ margin: 0, color: '#5b4a36' }}>
            {paymentsPending} Rs to be paid for the last fortnight.
          </p>
        </article>
      </div>
    </section>
  )
}

// ============================================================
// Component: FarmerCard
// Displays information about a dairy farmer
// ============================================================
function FarmerCard() {
  const farmerName = "Ramesh Patil"
  const village = "Wadgaon"
  const milkQuantity = 18.5
  const fatPercentage = 4.6

  return (
    <article style={{
      backgroundColor: '#fdf8ee',
      border: '1px solid #ddc9a5',
      borderLeft: '6px solid #e8b84b',
      borderRadius: '8px',
      padding: '12px 18px',
      margin: '12px 0'
    }}>
      <h3 style={{ margin: '0 0 6px 0', color: '#6b4226' }}>
        Farmer: {farmerName}
      </h3>
      <p style={{ margin: 0, color: '#5b4a36' }}>
        Village: {village} | Milk: {milkQuantity} litres | Fat: {fatPercentage}%
      </p>
    </article>
  )
}

// ============================================================
// Component: MilkCollectionCard
// Displays milk collection details
// ============================================================
function MilkCollectionCard() {
  const collectionDate = "21 September 2026"
  const shift = "Morning"
  const snf = 8.9

  return (
    <article style={{
      backgroundColor: '#fdf8ee',
      border: '1px solid #ddc9a5',
      borderLeft: '6px solid #e8b84b',
      borderRadius: '8px',
      padding: '12px 18px',
      margin: '12px 0'
    }}>
      <h3 style={{ margin: '0 0 6px 0', color: '#6b4226' }}>
        Milk Collection Details
      </h3>
      <p style={{ margin: 0, color: '#5b4a36' }}>
        Date: {collectionDate} | Shift: {shift} | SNF: {snf}%
      </p>
    </article>
  )
}

// ============================================================
// Component: Footer
// Renders the footer section with contact and copyright info
// ============================================================
function Footer() {
  const currentYear = 2026
  const contactInfo = "Dairy Cooperative Society Office, Main Road, Village | Phone: 98765 43210"
  const copyrightText = `© ${currentYear} Dairy Management System - College Web Development Project`

  return (
    <footer style={{
      backgroundColor: '#4b2e0e',
      color: '#f5e6c8',
      textAlign: 'center',
      padding: '18px 10px',
      marginTop: '20px'
    }}>
      <p style={{ margin: '4px 0' }}>{contactInfo}</p>
      <p style={{ margin: '4px 0' }}>{copyrightText}</p>
    </footer>
  )
}

// ============================================================
// Component: App
// Root component - combines all child components (SPA structure)
// ============================================================
function App() {
  return (
    <div className="App">
      <Header />
      <main>
        <Dashboard />
        <section>
          <h2 style={{
            color: '#7a4a21',
            borderBottom: '2px solid #d9c4a3',
            paddingBottom: '6px'
          }}>
            Latest Milk Collections
          </h2>
          <FarmerCard />
          <MilkCollectionCard />
        </section>
      </main>
      <Footer />
    </div>
  )
}

export default App
