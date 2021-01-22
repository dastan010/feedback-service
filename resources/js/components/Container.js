import React from 'react'

function Container({title, children}) {
  return (
    <div className="container">
      <div className="card">
        <div className="card-header">
          {title}
        </div>
        <div className="card-body">
          {children}
        </div>
      </div>
    </div>
  )
}

export default Container