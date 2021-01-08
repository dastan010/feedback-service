import React from 'react'
import ReactDOM from 'react-dom'
import {Link} from 'react-router-dom'
import Container from './Container'
function AddTicket() {
  return (
    <Container title="Add Ticket">
        <Link to="/" className="btn btn-primary">Back</Link>
        <br/>
        <form className="mt-4">
          <div className="form-group">
            <label>
              Theme:
            </label>
            <input className="form-control" type="text" name="theme" />
          </div>
          <div className="form-group">
            <label>
              Message:
            </label>
            <textarea className="form-control" type="text" name="theme" />
          </div>
          <div className="form-group">
            <div className="d-flex justify-content-center">
              <button className="btn btn-success">Send</button>
            </div>
          </div>
        </form>
    </Container>
  )
}

export default AddTicket
