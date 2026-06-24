import './App.css';

function App() {
  return (
    <div className="App">

      {/* SIDEBAR */}
      <div className="sidebar">
        <h2>TotoBora</h2>
        <a href="#">Dashboard</a>
        <a href="#">Register Child</a>
        <a href="#">Immunization</a>
        <a href="#">Growth Monitoring</a>
        <a href="#">Appointments</a>
        <a href="#">Reports</a>
      </div>

      {/* MAIN CONTENT */}
      <div className="main-content">

        <div className="topbar">
          <h3>Healthcare Worker Dashboard</h3>
          <button>Logout</button>
        </div>

        {/* CARDS */}
        <div className="card-grid">

          <div className="card">
            <h4>Total Children</h4>
            <p>128</p>
          </div>

          <div className="card">
            <h4>Pending Vaccinations</h4>
            <p>32</p>
          </div>

          <div className="card">
            <h4>Missed Appointments</h4>
            <p>10</p>
          </div>

          <div className="card">
            <h4>Growth Alerts</h4>
            <p>5</p>
          </div>

        </div>

      </div>
    </div>
  );
}

export default App;
