import React, {useState} from 'react'
import {useHistory, Link} from 'react-router-dom'
import Container from '../Container'
import api from '../../requests'

function AddTicket() {
  const history = useHistory(),
        [loading, setLoading] = useState(false),
        [theme, setTheme] = useState(''),
        [message, setMessage] = useState(''),
        [file, setFile] = useState(null),
        [dataNotification, setDataNotification] = useState('')
  const onAddSubmit = async() => {
    setLoading(true)
    try {
      const formData = new FormData()
      formData.append('attachedFile', file)
      formData.append('theme', theme)
      formData.append('message', message) 
      await api.createTicket(formData).then(res => {
        res.data.alert 
          ? setDataNotification(res.data.alert)
          : setDataNotification(res.data.success)
      })
    } catch(error) {
        alert('Failed to add Ticket!')
    } finally {
      setLoading(false)
      setTimeout(() => history.push('/'), 1000)
    }
  }

  function Notification(props) {
    return (
      <div className={`banner bg-warning ${props.data ? '' : 'hidden'}`}>{props.data}</div>
    )
  }

  
  const onFileChange = event => { 
    setFile(event.target.files[0]) 
    let extension = event.target.files[0].name.split('.').pop()
    if (extension !== 'docx') {
      alert('Файл должен быть формата docx!');
      document.getElementById('file').value = ''
    }
  }
  return (
    <Container title="Add Ticket">
      <Link to="/" className="btn btn-primary">Back</Link>
      <br/>
      <Notification data={dataNotification}/>
      <form className="mt-4" encType="multipart/form-data">
        <div className="form-group">
          <label>
            Theme:
          </label>
          <input 
            className="form-control" 
            type="text" 
            name="theme" 
            value={theme}
            onChange={e => setTheme(e.target.value)}/>
        </div>
        <div className="form-group">
          <label>
            Message:
          </label>
          <textarea 
            className="form-control" 
            type="text" 
            name="message" 
            value={message}
            onChange={e => setMessage(e.target.value)}
          />
        </div>
        <div className="form-group">
          <input id="file" type="file" onChange={onFileChange}/> 
        </div>
        <div className="form-group">
          <div className="d-flex justify-content-center">
            <button 
              type="button"
              className="btn btn-success"
              onClick={onAddSubmit}
              disabled={loading}
            >{loading ? 'Loading...' : 'Send'}</button>
          </div>
        </div>
      </form>
    </Container>
  )
}

export default AddTicket
