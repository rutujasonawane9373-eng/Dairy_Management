// ============================================================
// DAIRY MANAGEMENT SYSTEM - React Single Page Application
//
// ASSIGNMENT 8 : React Components and JSX
//               (components, JSX, expressions, className)
// ASSIGNMENT 9 : React props, state, hooks, event handling
//               and conditional rendering
//               (see src/ApiFarmerList.jsx for Assignment 10)
//
// Demonstrates:
// - PROPS              : App passes data down to Header, Dashboard,
//                        StatCard, FarmerCard, MilkCollectionCard, Footer
// - STATE              : useState() - selected farmer, milk quantity,
//                        search text, farmer details, payment status,
//                        high collection threshold
// - HOOKS              : only useState is used in this file, so the
//                        farmer register below works without any
//                        network request. Assignment 10 (useEffect +
//                        fetch) lives in its own file,
//                        src/ApiFarmerList.jsx, and is rendered at the
//                        end of <main>.
// - EVENT HANDLING     : onClick on the buttons, onChange on the search
//                        box and on the threshold drop-down
// - CONDITIONAL RENDERING : High / Normal collection message,
//                        Paid / Pending badge, the farmer details block
//                        and the "no farmer found" message
// ============================================================

import { useState } from 'react'
// ASSIGNMENT 10 : the section that fetches farmer data as JSON
// from a public API with fetch() inside useEffect().
import ApiFarmerList from './ApiFarmerList.jsx'
import './App.css'

// ============================================================
// Demo data
// The farmers registered with the society. This is plain data kept
// in this file - Assignment 13 will store it in MySQL. There is no
// fetch() call and no JSON file in THIS file: the API farmer list
// of Assignment 10 is a separate component, src/ApiFarmerList.jsx.
// ============================================================
const farmersData = [
  {
    farmerName: 'Ramesh Patil',
    village: 'Wadgaon',
    milkQuantity: 18.5,
    fatPercentage: 4.6,
    snf: 8.9,
    rate: 44,
    shift: 'Morning',
    mobile: '9876543210',
    memberId: 'DMS-101'
  },
  {
    farmerName: 'Sunita Jadhav',
    village: 'Wadgaon',
    milkQuantity: 12.0,
    fatPercentage: 3.8,
    snf: 8.2,
    rate: 40,
    shift: 'Morning',
    mobile: '9823456710',
    memberId: 'DMS-102'
  },
  {
    farmerName: 'Vilas More',
    village: 'Pimpri',
    milkQuantity: 22.5,
    fatPercentage: 4.9,
    snf: 9.1,
    rate: 45,
    shift: 'Evening',
    mobile: '9765432198',
    memberId: 'DMS-103'
  },
  {
    farmerName: 'Anita Deshmukh',
    village: 'Pimpri',
    milkQuantity: 15.0,
    fatPercentage: 4.2,
    snf: 8.6,
    rate: 42,
    shift: 'Morning',
    mobile: '9654321987',
    memberId: 'DMS-104'
  },
  {
    farmerName: 'Ganesh Pawar',
    village: 'Nagav',
    milkQuantity: 9.5,
    fatPercentage: 3.9,
    snf: 8.4,
    rate: 40,
    shift: 'Evening',
    mobile: '9543219876',
    memberId: 'DMS-105'
  },
  {
    farmerName: 'Meena Kulkarni',
    village: 'Nagav',
    milkQuantity: 20.0,
    fatPercentage: 4.7,
    snf: 9.0,
    rate: 44,
    shift: 'Morning',
    mobile: '9432109876',
    memberId: 'DMS-106'
  }
]

// Fixed texts of the page - passed to Header and Footer as props.
const siteTitle = 'Dairy Management System'
const tagline = 'Your daily partner for milk collection, fat & SNF tracking and farmer payments.'
const collectionDate = '21 September 2026'
const currentYear = 2026
const contactInfo = 'Dairy Cooperative Society Office, Main Road, Village | Phone: 98765 43210'

// Small helpers so that every number is written the same way.
function formatLitres(litres) {
  return litres.toFixed(1)
}

function formatMoney(amount) {
  return Math.round(amount) + ' Rs'
}

// ============================================================
// Component: Header
// PROPS: siteTitle, tagline are given by App, so the same component
// can show a different title if it is reused somewhere else.
// ============================================================
function Header({ siteTitle, tagline }) {
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
// Component: StatCard
// One small overview card. It was four repeated <article> blocks
// inside Dashboard in Assignment 8; making it a component shows why
// props are useful - the card is written once and used four times.
// PROPS: title, description, isAlert (changes the colours)
// ============================================================
function StatCard({ title, description, isAlert }) {
  // Conditional class name: the alert card uses the orange colours.
  const cardClass = isAlert ? 'stat-card stat-card-alert' : 'stat-card'

  return (
    <article className={cardClass}>
      <h3 className="stat-card-title">{title}</h3>
      <p className="stat-card-text">{description}</p>
    </article>
  )
}

// ============================================================
// Component: Dashboard
// The four overview cards of the Dairy Management System.
// PROPS: totalFarmers, litresToday, averageFat, paymentsPending,
//        highMilkThreshold
// ============================================================
function Dashboard({ totalFarmers, litresToday, averageFat, paymentsPending, highMilkThreshold }) {
  return (
    <section>
      <h2 className="section-title">
        Today&apos;s Overview
      </h2>

      <div className="cards">
        <StatCard
          title="Total Farmers"
          description={totalFarmers + ' registered milk producers in the village area.'}
        />
        <StatCard
          title="Milk Collected Today"
          description={formatLitres(litresToday) + ' litres from ' + totalFarmers + ' farmers.'}
        />
        <StatCard
          title="Average Fat"
          description={averageFat.toFixed(1) + '% average fat content this week.'}
        />

        {/* CONDITIONAL RENDERING: the fourth card changes when nothing is left to pay */}
        {paymentsPending > 0 ? (
          <StatCard
            isAlert
            title="Payments Pending"
            description={formatMoney(paymentsPending) + ' to be paid for the last fortnight.'}
          />
        ) : (
          <StatCard
            isAlert
            title="Payments Settled"
            description="Every farmer of today's collection has been paid."
          />
        )}
      </div>

      {/* CONDITIONAL RENDERING: one of the two messages is drawn,
          depending on whether the day total reaches the threshold */}
      {litresToday >= highMilkThreshold ? (
        <p className="badge badge-high">
          High Milk Collection - {formatLitres(litresToday)} litres today, at or above the{' '}
          {highMilkThreshold} litre threshold.
        </p>
      ) : (
        <p className="badge badge-normal">
          Normal Milk Collection - {formatLitres(litresToday)} litres today, below the{' '}
          {highMilkThreshold} litre threshold.
        </p>
      )}
    </section>
  )
}

// ============================================================
// Component: FarmerCard
// Shows one farmer of the collection register.
// PROPS (data)     : farmerName, village, milkQuantity, fatPercentage,
//                    snf, rate, shift, memberId, mobile
// PROPS (UI state) : isSelected, showDetails, isPaid
// PROPS (functions): onSelect, onTogglePayment - these are the
//                    handlers created in App, so clicking here can
//                    change the state that lives in App.
// ============================================================
function FarmerCard({
  farmerName,
  village,
  milkQuantity,
  fatPercentage,
  snf,
  rate,
  shift,
  memberId,
  mobile,
  isSelected,
  showDetails,
  isPaid,
  onSelect,
  onTogglePayment
}) {
  return (
    <article className={isSelected ? 'farmer-card farmer-card-selected' : 'farmer-card'}>
      <h3 className="card-title">Farmer: {farmerName}</h3>
      <p className="card-text">
        Village: {village} | Milk: {formatLitres(milkQuantity)} litres | Fat: {fatPercentage}%
      </p>

      {/* CONDITIONAL RENDERING: the details block is drawn only when
          the showDetails state of App is true */}
      {showDetails && (
        <div className="farmer-details">
          <p>Member ID: {memberId} | Mobile: {mobile}</p>
          <p>SNF: {snf}% | Shift: {shift} | Rate: {rate} Rs/litre</p>
          <p>Estimated payment: {formatMoney(milkQuantity * rate)}</p>
        </div>
      )}

      {/* CONDITIONAL RENDERING: paid / pending badge */}
      {isPaid ? (
        <span className="badge badge-paid">Payment Received</span>
      ) : (
        <span className="badge badge-pending">Payment Pending</span>
      )}

      <div className="card-buttons">
        {/* EVENT HANDLING: onClick calls the handler received as a prop */}
        <button className="btn" onClick={() => onSelect(farmerName)}>
          {isSelected ? 'Selected Farmer' : 'Select Farmer'}
        </button>
        <button className="btn btn-secondary" onClick={() => onTogglePayment(farmerName)}>
          {isPaid ? 'Mark as Unpaid' : 'Mark as Paid'}
        </button>
      </div>
    </article>
  )
}

// ============================================================
// Component: MilkCollectionCard
// The collection of the farmer that is currently selected, with the
// + / - buttons that change the milk quantity.
// PROPS: farmerName, collectionDate, shift, snf, rate, milkQuantity,
//        highMilkThreshold, onIncrease, onDecrease
// ============================================================
function MilkCollectionCard({
  farmerName,
  collectionDate,
  shift,
  snf,
  rate,
  milkQuantity,
  highMilkThreshold,
  onIncrease,
  onDecrease
}) {
  return (
    <article className="milk-card">
      <h3 className="card-title">Milk Collection Details</h3>
      <p className="card-text">
        Farmer: {farmerName} | Date: {collectionDate} | Shift: {shift} | SNF: {snf}%
      </p>
      <p className="card-text">
        Rate: {rate} Rs/litre | Estimated payment: {formatMoney(milkQuantity * rate)}
      </p>

      {/* EVENT HANDLING: onClick changes the milkQuantity state in App */}
      <div className="quantity-box">
        <button className="btn" onClick={onDecrease} disabled={milkQuantity <= 0}>
          - 0.5 L
        </button>
        <span className="quantity-value">{formatLitres(milkQuantity)} litres</span>
        <button className="btn" onClick={onIncrease}>
          + 0.5 L
        </button>
      </div>

      {/* CONDITIONAL RENDERING: this farmer's collection compared with the threshold */}
      {milkQuantity >= highMilkThreshold ? (
        <p className="badge badge-high">High Milk Collection</p>
      ) : (
        <p className="badge badge-normal">Normal Milk Collection</p>
      )}
    </article>
  )
}

// ============================================================
// Component: Footer
// PROPS: currentYear, contactInfo
// ============================================================
function Footer({ currentYear, contactInfo }) {
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
// Root component. It owns the STATE and passes everything the
// children need down as PROPS.
// ============================================================

// The HOOK used in this assignment: useState.
// const [currentValue, setCurrentValue] = useState(initialValue)
// currentValue is read while rendering, setCurrentValue() changes it
// and React draws the component again with the new value.
function App() {
  // ---------- STATE (useState) ----------

  // Which farmer is being worked on, and the farmer object itself.
  const [selectedFarmerName, setSelectedFarmerName] = useState(farmersData[0].farmerName)

  // Litres of the selected farmer. Changed by the + / - buttons.
  const [milkQuantity, setMilkQuantity] = useState(farmersData[0].milkQuantity)

  // What the user has typed in the search box.
  const [searchText, setSearchText] = useState('')

  // True = farmer details are visible, false = they are hidden.
  const [showFarmerDetails, setShowFarmerDetails] = useState(false)

  // Names of the farmers whose payment is done.
  const [paidFarmerNames, setPaidFarmerNames] = useState([])

  // Litres from which a collection is called a high collection.
  const [highMilkThreshold, setHighMilkThreshold] = useState(20)

  // ---------- EVENT HANDLERS (called by the children's onClick) ----------

  // Selects a farmer and loads that farmer's recorded quantity.
  function selectFarmer(name) {
    const farmer = farmersData.find((item) => item.farmerName === name)
    setSelectedFarmerName(name)
    setMilkQuantity(farmer.milkQuantity)
  }

  // + 0.5 litres. The arrow function form of setMilkQuantity receives
  // the current value, so no stale value is used.
  function increaseMilk() {
    setMilkQuantity((current) => Math.round((current + 0.5) * 10) / 10)
  }

  // - 0.5 litres, never below zero.
  function decreaseMilk() {
    setMilkQuantity((current) => Math.max(0, Math.round((current - 0.5) * 10) / 10))
  }

  // Adds the name to the paid list, or removes it from the list.
  function togglePayment(name) {
    setPaidFarmerNames((current) => {
      if (current.includes(name)) {
        return current.filter((item) => item !== name)
      }
      return [...current, name]
    })
  }

  // ---------- VALUES CALCULATED FROM THE STATE ----------

  // The farmer object of the selected name.
  const selectedFarmer = farmersData.find((farmer) => farmer.farmerName === selectedFarmerName)

  // The search box filters by farmer name or village.
  const typedText = searchText.trim().toLowerCase()
  const visibleFarmers = farmersData.filter((farmer) => {
    return (
      farmer.farmerName.toLowerCase().includes(typedText) ||
      farmer.village.toLowerCase().includes(typedText)
    )
  })

  // Litres of the whole day: every recorded quantity, with the
  // selected farmer's quantity replaced by the adjusted one.
  const litresToday = farmersData.reduce((total, farmer) => {
    const litres =
      farmer.farmerName === selectedFarmerName ? milkQuantity : farmer.milkQuantity
    return total + litres
  }, 0)

  const averageFat =
    farmersData.reduce((total, farmer) => total + farmer.fatPercentage, 0) / farmersData.length

  // Money still to be paid = the unpaid farmers' litres x their rate.
  const paymentsPending = farmersData
    .filter((farmer) => !paidFarmerNames.includes(farmer.farmerName))
    .reduce((total, farmer) => {
      const litres =
        farmer.farmerName === selectedFarmerName ? milkQuantity : farmer.milkQuantity
      return total + litres * farmer.rate
    }, 0)

  return (
    <div className="App">
      {/* PROPS: App gives Header its two texts */}
      <Header siteTitle={siteTitle} tagline={tagline} />

      <main>
        {/* PROPS: App gives Dashboard the numbers of the day */}
        <Dashboard
          totalFarmers={farmersData.length}
          litresToday={litresToday}
          averageFat={averageFat}
          paymentsPending={paymentsPending}
          highMilkThreshold={highMilkThreshold}
        />

        <section>
          <h2 className="section-title">
            Latest Milk Collections
          </h2>

          {/* Toolbar of the register: search box, threshold drop-down
              and the Show / Hide Farmer Details button. */}
          <div className="toolbar">
            <label htmlFor="farmer-search">Search farmer</label>
            {/* EVENT HANDLING: onChange fires on every keystroke and writes
                the typed value into the searchText state */}
            <input
              id="farmer-search"
              type="search"
              className="field-input"
              placeholder="Name or village"
              value={searchText}
              onChange={(event) => setSearchText(event.target.value)}
            />

            <label htmlFor="threshold-select">High collection from</label>
            {/* EVENT HANDLING: onChange of a drop-down also updates state */}
            <select
              id="threshold-select"
              className="field-input"
              value={highMilkThreshold}
              onChange={(event) => setHighMilkThreshold(Number(event.target.value))}
            >
              <option value="15">15 litres</option>
              <option value="20">20 litres</option>
              <option value="25">25 litres</option>
            </select>

            {/* EVENT HANDLING: onClick flips the showFarmerDetails state */}
            <button
              className="btn btn-secondary"
              onClick={() => setShowFarmerDetails((current) => !current)}
            >
              {showFarmerDetails ? 'Hide Farmer Details' : 'Show Farmer Details'}
            </button>

            <span className="toolbar-note">
              {visibleFarmers.length} of {farmersData.length} farmers shown
            </span>
          </div>

          {/* CONDITIONAL RENDERING: the list, or a message when the
              search matches no farmer */}
          {visibleFarmers.length === 0 ? (
            <p className="no-result">
              {'No farmer found for "' + searchText + '". Clear the search box to see all farmers.'}
            </p>
          ) : (
            visibleFarmers.map((farmer) => (
              <FarmerCard
                key={farmer.farmerName}
                farmerName={farmer.farmerName}
                village={farmer.village}
                milkQuantity={
                  farmer.farmerName === selectedFarmerName ? milkQuantity : farmer.milkQuantity
                }
                fatPercentage={farmer.fatPercentage}
                snf={farmer.snf}
                rate={farmer.rate}
                shift={farmer.shift}
                memberId={farmer.memberId}
                mobile={farmer.mobile}
                isSelected={farmer.farmerName === selectedFarmerName}
                showDetails={showFarmerDetails}
                isPaid={paidFarmerNames.includes(farmer.farmerName)}
                onSelect={selectFarmer}
                onTogglePayment={togglePayment}
              />
            ))
          )}

          {/* PROPS: the collection of the selected farmer */}
          <MilkCollectionCard
            farmerName={selectedFarmerName}
            collectionDate={collectionDate}
            shift={selectedFarmer.shift}
            snf={selectedFarmer.snf}
            rate={selectedFarmer.rate}
            milkQuantity={milkQuantity}
            highMilkThreshold={highMilkThreshold}
            onIncrease={increaseMilk}
            onDecrease={decreaseMilk}
          />
        </section>

        {/* ASSIGNMENT 10 : FETCH API + JSON
            This section gets its farmer records from a public API with
            fetch() inside useEffect(), converts the answer with
            response.json() and keeps the data in state. All of that
            code is in src/ApiFarmerList.jsx. */}
        <ApiFarmerList />
      </main>

      {/* PROPS: App gives Footer the year and the contact line */}
      <Footer currentYear={currentYear} contactInfo={contactInfo} />
    </div>
  )
}

export default App