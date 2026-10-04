// ============================================================
// DAIRY MANAGEMENT SYSTEM
//
// ASSIGNMENT 10 : Fetch API and JSON
// "Fetch and display API data using Fetch and JSON."
//
// Demonstrates:
// - API          : a free public test API that returns JSON
// - fetch()      : the browser's built-in function for HTTP requests
// - JSON         : the text format the API answers in
// - response.ok  : checks that the HTTP request really succeeded
// - response.json(): converts the received text into JavaScript data
// - useEffect()  : runs the request once, when the component loads
// - useState()   : stores the JSON data, the loading flag and the
//                  error message
// - map() + key  : draws one card per API record
//
// The whole flow of this file:
//
//   API -> fetch() -> HTTP response -> response.json()
//       -> JSON data -> React state -> UI
//
// There is NO backend of our own here: no PHP, no MySQL, no
// Node/Express and no HTTP library such as Axios. Only the
// fetch() function that is built into the browser is used.
// ============================================================

import { useEffect, useState } from 'react'

// ============================================================
// The API URL
// A free public test API. It answers with a JSON array of 10
// user records. It is read only, needs no key and is safe for a
// college demonstration.
// ============================================================
const API_URL = 'https://jsonplaceholder.typicode.com/users'

// ============================================================
// fetchFarmerData()
// Sends the request to the API and returns the JSON data.
// Every step of the fetch flow has its own comment below.
// This function only gets the data - it does NOT touch React,
// so the same function could be reused anywhere.
// ============================================================
async function fetchFarmerData() {
  // STEP 1 - fetch() sends the GET request to the API URL.
  // It does not return the data directly; it returns a Promise
  // that resolves later with a "response" object.
  const response = await fetch(API_URL)

  // STEP 2 - check response.ok.
  // fetch() only rejects when the network is completely broken.
  // A 404 (page not found) or a 500 (server error) still arrives
  // as a normal response, so we must test response.ok ourselves
  // (it is true for status codes 200-299).
  if (!response.ok) {
    throw new Error('API request failed with status ' + response.status)
  }

  // STEP 3 - response.json().
  // The body of the response is received as TEXT. response.json()
  // reads that text and converts it into real JavaScript data
  // (here: an array of objects). It also returns a Promise, which
  // is why we write await in front of it.
  const data = await response.json()

  return data
}

// ============================================================
// Component: ApiFarmerCard
// Draws ONE farmer record received from the API.
// The record fields used here are: id, name, username, email and
// address.city. PROPS: farmer - the single object from the JSON.
// ============================================================
function ApiFarmerCard({ farmer }) {
  return (
    <article className="farmer-card api-farmer-card">
      <h3 className="card-title">{farmer.name}</h3>
      <p className="card-text">Username: {farmer.username}</p>
      <p className="card-text">Email: {farmer.email}</p>
      {/* The city is inside a nested object, so we read
          address.city - this shows how nested JSON is used. */}
      <p className="card-text">City: {farmer.address.city}</p>
      {/* The id of the record, taken from the same JSON object. */}
      <span className="badge badge-normal">API record #{farmer.id}</span>
    </article>
  )
}

// ============================================================
// Component: ApiFarmerList
// The "Farmer Information from API" section of the Dairy
// Management page. It fetches the data when it loads and shows
// the loading message, the error message or the records.
// ============================================================
function ApiFarmerList() {
  // ---------- STATE (useState) ----------

  // The farmer records received from the API. It starts as an
  // empty array, so map() has nothing to draw until the data
  // arrives.
  const [apiFarmers, setApiFarmers] = useState([])

  // True while the request is running. Because it starts as true,
  // the screen shows "Loading farmer data..." immediately instead
  // of staying blank, and retryLoad() sets it back to true.
  const [loading, setLoading] = useState(true)

  // A short, friendly message for the user. It is an empty string
  // when there is no error. The real technical error is written
  // only to the browser console, never shown on the page.
  const [error, setError] = useState('')

  // Runs the API request and stores the answer in the state above.
  // It is called once from useEffect() when the page loads, and
  // again by retryLoad() if the request failed.
  async function loadFarmerData() {
    try {
      const data = await fetchFarmerData()

      // STATE UPDATE: the JSON data is stored in apiFarmers, so
      // React draws the component again with the new records.
      setApiFarmers(data)
    } catch (technicalError) {
      // ERROR HANDLING: something went wrong - no connection,
      // server down, or a status code that is not 2xx.
      console.error('Could not load farmer data from the API:', technicalError)

      // Only the friendly message is shown to the user.
      setError('Unable to load farmer data. Please try again.')
    } finally {
      // LOADING FINISHED: whether the request worked or not, the
      // loading message must disappear.
      setLoading(false)
    }
  }

  // The Retry button. It puts the states back to "loading" and then
  // calls the very same fetch function again.
  function retryLoad() {
    setLoading(true)
    setError('')
    loadFarmerData()
  }

  // ---------- useEffect ----------
  // useEffect() registers work that must happen AFTER the component
  // has been rendered. The empty array [] is the dependency list:
  // it is empty, so this effect runs only ONCE, when the component
  // loads - not on every re-render caused by a state change.
  useEffect(() => {
    // Every setState() call inside loadFarmerData() happens after an
    // await(), so nothing is updated synchronously - this rule does not
    // follow the async function and reports a false warning.
    // oxlint-disable-next-line react/set-state-in-effect
    loadFarmerData()
  }, [])

  return (
    <section className="api-section">
      <h2 className="section-title">Farmer Information from API</h2>

      <p className="api-note">
        The farmer records below are fetched as JSON from <code>{API_URL}</code> using the
        browser&apos;s <code>fetch()</code> function.
      </p>

      {/* CONDITIONAL RENDERING: only one of the three blocks is drawn,
          decided by the loading and error state. */}

      {/* 1. The request is still running. */}
      {loading && <p className="api-message">Loading farmer data...</p>}

      {/* 2. The request failed - message plus a Retry button that
          calls the same fetch function again. */}
      {!loading && error && (
        <div className="api-message-box">
          <p className="api-message api-error">{error}</p>
          <button className="btn btn-secondary" onClick={retryLoad}>
            Retry
          </button>
        </div>
      )}

      {/* 3. The data arrived - draw one card per record with map().
          key={farmer.id} gives every item a unique identity, which
          React needs to update the list correctly. */}
      {!loading && !error && (
        <>
          <p className="api-note">{apiFarmers.length} farmer records received from the API.</p>

          {apiFarmers.map((farmer) => (
            <ApiFarmerCard key={farmer.id} farmer={farmer} />
          ))}
        </>
      )}
    </section>
  )
}

export default ApiFarmerList