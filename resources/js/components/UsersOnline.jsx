import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./tickets/TicketListEntry";
import {Link} from "react-router-dom";

export default class UsersOnline extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            data: []
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
            this.getOnlineUsers();
        };

    }

    async getOnlineUsers() {
        try {
            const response = await axios.get('/api/users/online');

            this.setState({ data: response.data });
        } catch {
        }
    }

    render() {
        let body = <div className="text-center"><Spinner animation="border" /></div>;
        if(this.state.data.length > 0) {
            let first = this.state.data[0];

            body = <table className="table table-sm">
                <thead>
                <tr>
                    <th>Name</th>
                    {first.Time ? <th>Zeit</th> : ''}
                    {first.Url ? <th>Url</th> : ''}
                </tr>
                </thead>
                <tbody>
                {this.state.data.map((user) => {
                    return <tr>
                        <td>{user.Name}</td>
                        {user.Time ? <th>{user.Time}</th> : ''}
                        {user.Url ? <th><a href={user.Url}>{user.Url}</a></th> : ''}
                    </tr>;
                })}
                </tbody>
            </table>;
        }

        return (
            <>
                <a onClick={this.handleShow} href="#">
                    {this.props.children}
                </a>

                <Modal show={this.state.show} onHide={this.handleClose}>
                    <Modal.Header closeButton>
                        <Modal.Title>Benutzer online</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {body}
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
            </>
        );
    }
}

var banDialogs = document.getElementsByTagName('react-users-online');

for (var index in banDialogs) {
    const component = banDialogs[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<UsersOnline {...props} />, component);
    }
}

