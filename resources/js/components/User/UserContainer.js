import React from 'react'
import ReactDOM from 'react-dom'
import {
  BrowserRouter as Router,
  Switch,
  Route
} from 'react-router-dom'

import Tickets from './Tickets'
import AddTicket from './AddTicket'

function UserContainer() {
  return (
    <Router className="UserContainer">
      <Switch>
        <Route exact path="/">
          <Tickets />
        </Route>
        <Route path="/add">
          <AddTicket />
        </Route>
      </Switch>
    </Router>
  )   
}



document.getElementById('userContainer')
  ? ReactDOM.render(<UserContainer />, document.getElementById('userContainer'))
  : console.log('Node isn\'t provided!')