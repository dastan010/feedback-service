import React, {useEffect, useState} from 'react'
import { Link } from 'react-router-dom'
import Container from '../Container'
import api from '../../requests'

function Tickets() {
    const [tickets, setTickets] = useState(null)
    const fetchTickets = () => {
        api.getAllTickets().then(res => {
            setTickets(res.data.tickets)
        })
    }
    useEffect(()=>{
        fetchTickets()
    },[])

    const renderTickets = () => {
        if (!tickets) {
            return (
                <tr>
                    <td colSpan="4">
                        Loading tickets...
                    </td>
                </tr>
            )
        }

        if (tickets.length === 0) {
            return (
                <tr>
                    <td colSpan="4">
                        There is no tickets yet. Add one.
                    </td>
                </tr>
            )
        }

        
        return tickets.map((ticket, index) => (
            <tr key={index}>
              <td>{index+1}</td>
              <td>{ticket.theme}</td>
              <td>{ticket.message}</td>
              <td>{ticket.response}</td>
            </tr>
        ))
    }
    return (
        <Container title="Tickets">
            <Link to="/add" className="btn btn-primary">Новый запрос</Link>
            <div className="table-responsive">
                <table className="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID.</th>
                        <th>Theme</th>
                        <th>Message</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    {renderTickets()}
                </tbody>
                </table>
            </div>
        </Container>
    );
}

export default Tickets;
