import React, {useEffect, useState} from 'react'
import ReactDom from 'react-dom'
import api from '../../requests'
import Container from '../Container'
import Modal from './Modal'

function AdminContainer() {
  const [users, setUsers] = useState(null)
  const [userTickets, setUserTickets] = useState(null)
  const [propTicket, setPropTicket] = useState(null)
  const fetchUsers = () => {
    api.getUsers().then(res => {
      if (res.data.users) {
        setUsers(res.data.users)
        fetchUserTickets(res.data.users[0].id)
      }
    })
  }

  const fetchUserTickets = id => {
    api.getUserTickets(id).then(res => {
      setPropTicket('')
      setUserTickets(res.data.userTickets)
    })
  }

  useEffect(()=> {
    fetchUsers()
  },[])

  

  const renderUsers = () =>  {
    if (!users) {
      return (
        <option>Loading users...</option>
      )
    }

    if (users.lenght === 0) {
      return (
        <option>There is no users...</option>
      )
    }

    return users.map((user, index) => (
      <option key={index} value={user.id}>{user.email}</option>
    ))
  }
  
  const renderUserTickets = () => {
    if (!userTickets) {
      return (
        <tr>
          <td colSpan="7">
            Loading tickets...
          </td>
        </tr>
      )
    }

    if(userTickets.length === 0) {
      return (
        <tr>
          <td colSpan="8">
            There are no tickets yet...
          </td>
        </tr>
      )
    }
    
    return userTickets.map((ticket, index) => (
      <tr key={index}>
        <td>{ticket.id}</td>
        <td>{ticket.name}</td>
        <td>{ticket.theme}</td>
        <td>{ticket.message}</td>
        <td>{ticket.response}</td>
        <td>{ticket.email}</td>
        <td>{ticket.created_at}</td>
        <td>
          <button 
            className="btn btn-primary"
            data-toggle="modal" 
            data-target="#exampleModal"
            onClick={() => {setPropTicket([ticket.user_id, ticket.id, ticket.response])}}
          >{ticket.response ? 'Редактировать ответ' : 'Ответить'}</button>
        </td>
        <td>
          <button 
            className={`btn btn-primary ${ticket.file_path ? '' : 'hidden'}`}
            onClick={() => {downloadFile(ticket.id, ticket.user_id)}}
          >Загрузить</button>
          <span className={`badge badge-warning customBadge ${ticket.file_path ? 'hidden' : ''}`}>Без файла</span>
        </td>
      </tr>
    ))
  }

  const change = e =>  {
    fetchUserTickets(e.target.value)  
  }

  const renderFromChild = id => {
    fetchUserTickets(id)
    renderUserTickets()
  }

  const downloadFile = (ticket_id, user_id) => {
    api.downloadFile(ticket_id, user_id).then(res => {
      const url = window.URL.createObjectURL(new Blob([res.data]));
      const link = document.createElement('a');
      link.href = url;
      link.setAttribute('download', 'file.docx'); 
      document.body.appendChild(link);
      link.click();
    })
  }
  
  return (
    <Container title="Admin">
      <Modal data={propTicket} parentCallback={renderFromChild}/>
      <div className="input-group mb-3">
        <div className="input-group-prepend">
          <label className="input-group-text" htmlFor="inputGroupSelect01">Options</label>
        </div>
        <select className="custom-select" onChange={change} id="inputGroupSelect01">
          {renderUsers()}
        </select>
      </div>
      <table className="table table-striped mt-4">
        <thead>
          <tr>
            <th>ID.</th>
            <th>Name</th>
            <th>Theme</th>
            <th>Message</th>
            <th>Response</th>
            <th>Mail</th>
            <th>Creation Time</th>
            <th>Actions</th>
            <th>Файл</th>
          </tr>
        </thead>
        <tbody>
          {renderUserTickets()}
        </tbody>
      </table>
    </Container>
  )
}

document.getElementById('adminContainer')
  ? ReactDom.render(<AdminContainer />, document.getElementById('adminContainer'))
  : console.log('The node isn\'t provided!')