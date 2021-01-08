import React from 'react';
import ReactDOM from 'react-dom';
import { Link } from 'react-router-dom';
import Container from './Container'

function User() {
    return (
        <Container title="Tickets">
            <Link to="/add" className="btn btn-primary">Новый запрос</Link>
            <div className="table-responsive">
                <table className="table table-striped mt-4">
                <thead>
                    <tr>
                        <th>ID.</th>
                        <th>Message</th>
                        <th>Answer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>lasdad dasdad daslskdasdj asd klaajdaalkdsa daslskdjasldkj dlksajdlsjkad</td>
                        <td>akjsdsakda dkjahsadkahd dakjsdakdh</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </Container>
    );
}

export default User;
