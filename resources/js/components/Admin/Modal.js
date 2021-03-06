import React, {useEffect, useState} from 'react'
import api from '../../requests'

function Modal(props) {
  let textAreaInput = React.createRef()
  useEffect(() => {
    props.data ? textAreaInput.current.value = props.data[2] : ''
  })
  const onSendResponse = async() => {
    if (textAreaInput.current.value) {
      let data = {
        user_id: props.data[0],
        ticket_id: props.data[1],
        response: textAreaInput.current.value
      }
      
      try {
        await api.responseToTicket(data.ticket_id, data).then(res => {
          props.parentCallback(res.data.user_id)
          textAreaInput.current.value = ''
        })
      } catch(e) {
        alert(e)
      }
    } else {
      alert('Введите поля')
    }
  }
  
  const changeValue = value => {
    textAreaInput.current.value = value
  }
  
  return (
    <div className="modal fade" id="exampleModal" tabIndex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div className="modal-dialog" role="document">
        <div className="modal-content">
          <div className="modal-header">
            <h5 className="modal-title" id="exampleModalLabel">Modal</h5>
            <button type="button" className="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div className="modal-body">
            <form>
              <div className="form-group">
                <label>Response</label>
                <textarea 
                  className="form-control" 
                  type="text"
                  ref={textAreaInput}
                  onChange={e => changeValue(e.target.value)}
                />
              </div>
            </form>
          </div>
          <div className="modal-footer">
            <button type="button" className="btn btn-secondary" data-dismiss="modal">Close</button>
            <button onClick={onSendResponse} type="button" className="btn btn-primary">Save changes</button>
          </div>
        </div>
      </div>
    </div>
  )
}

export default Modal