import React, {useEffect, useState} from 'react'
import {Link} from 'react-router-dom'
import Container from '../Container'
import request from '../../requests'
import Pagination from 'react-js-pagination'


function Tickets() {
    const [ticketsData, setTicketsData] = useState(null)
    const fetchTickets = (pageNumber = 1) => {
        request.getAllTickets(pageNumber).then(res => {
            setTicketsData(res.data.tickets)    
        })
    }
    
    useEffect(()=>{
        fetchTickets()
    },[])

    const renderTickets = () => {
        if (!ticketsData) {
            return (
                <tr>
                    <td colSpan="4">
                        Loading tickets...
                    </td>
                </tr>
            )
        }

        
        if (ticketsData) {
            if (ticketsData.data.length === 0) {
                return (
                    <tr>
                        <td colSpan="4">
                            There is no tickets yet. Add one.
                        </td>
                    </tr>
                )
            }
            
            return ticketsData.data.map((ticket, index) => (
                <tr key={index}>
                  <td>{ticket.id}</td>
                  <td>{ticket.theme}</td>
                  <td>{ticket.message}</td>
                  <td>{ticket.response}</td>
                </tr>
            ))
        }
    }

    const renderTable = () => {
        if (ticketsData) {
            const {total, per_page, current_page} = ticketsData
            return (
                <React.Fragment>
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
                        <div className="d-flex justify-content-center">
                            <Pagination 
                                totalItemsCount={total} 
                                itemsCountPerPage={per_page}   
                                onChange={pageNumber => fetchTickets(pageNumber)}
                                activePage={current_page}
                                itemClass="page-item"
                                linkClass="page-link"
                            />
                        </div>
                </React.Fragment>
            )
        } else {
            return (
                <div className="container">
                    Loading Tickets...
                </div>
            )
        }
    }

    return (
        <Container title="Tickets">
            <Link to="/add" className="btn btn-primary">Новый запрос</Link>
            <div className="table-responsive">
                {renderTable()}
            </div>
        </Container>
    );
}

export default Tickets;
