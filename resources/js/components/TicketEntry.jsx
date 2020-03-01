import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, OverlayTrigger, Tooltip, Row, Col} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import SelectUserDialog from './SelectUserDialog';
import {
    Link,
    useParams
} from "react-router-dom";
import ConfirmDialog from './ConfirmDialog';
import AddUserDialog from "./AddUserDialog";

export default class TicketEntry extends Component {
    constructor({match}) {
        super();
        this.state = {
            data: null,
            message: '',
            ticketId: match.params.ticketId,
            showAddUserDialog: false,
            showRemoveUserDialog: false
        };
    }
    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData();
        }
    }

    async toggleAddUserDialog() {
        this.setState({showAddUserDialog: !this.state.showAddUserDialog});
    }

    async toggleRemoveUserDialog(userId) {
        this.setState({showRemoveUserDialog: !this.state.showRemoveUserDialog, removeUserId: userId});
    }

    async onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    async send() {
        try {
            const response = await axios.put('/api/tickets/' + this.state.ticketId, {
                type: 'addMessage',
                message: this.state.message,
            });

            this.setState({message: ''});
            this.loadData();
        } catch(error) {
            console.log(error);
        }
    }

    async close() {
        try {
            const response = await axios.put('/api/tickets/' + this.state.ticketId, {
                type: 'close',
            });

            this.loadData();
        } catch(error) {
            console.log(error);
        }
    }

    async loadData() {
        const response = await axios.get('/api/tickets/' + this.state.ticketId);

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    async addUser(userId) {
        try {
            const response = await axios.put('/api/tickets/' + this.state.ticketId, {
                type: 'addUser',
                newUserId: userId
            });

            this.loadData();
        } catch(error) {
            console.log(error);
        }
    }

    async removeUser() {

        try {
            const response = await axios.put('/api/tickets/' + this.state.ticketId, {
                type: 'removeUser',
                removeUserId: this.state.removeUserId
            });

            this.loadData();
        } catch(error) {
            console.log(error.response);
        }
    }

    render() {
        if(this.state.data === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        let closeButton = <></>;
        let answer = <></>;


        if(this.state.data.State === 'Open') {
            closeButton = <Button onClick={this.close.bind(this)} variant="danger">Ticket schließen</Button>;
            answer = (
                <Form>
                    <Form.Group>
                        <Form.Label>Nachricht</Form.Label>
                        <InputGroup>
                            <Form.Control as="textarea" rows="3" name="message" placeholder="Nachricht" value={this.state.message} onChange={this.onChange.bind(this)} />
                        </InputGroup>
                    </Form.Group>

                    <Button onClick={this.send.bind(this)} className="float-right">Senden</Button>
                </Form>
            );
        }

        return (
            <>
                <div className="row mb-4">
                    <div className="col-md-12">
                        <Link to="/tickets" className="btn btn-primary float-right">Zurück</Link>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <div className="row">
                            <div className="col-md-8">
                                <div className="card">
                                    <div className="card-header">
                                        Chat
                                    </div>
                                    <div className="card-body">
                                        <div className="chat">
                                            {this.state.data.answers.map((answer, i) => {
                                                if(parseInt(answer.MessageType) === 1) {
                                                    return (
                                                        <div key={answer.Id} className="chat-message">
                                                            <div className="message">
                                                                <div className="message-center">
                                                                    <div className="message-content">
                                                                        {answer.Message.split('\n').map((value, index) => {
                                                                            return <p key={index}>{value}</p>;
                                                                        })}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    );
                                                }

                                                return (
                                                    <div key={answer.Id} className="chat-message">
                                                        <div className="message">
                                                            <div className={answer.IsMyMessage ? 'message-right' : 'message-left'}>
                                                                <div className="message-content">
                                                                    <p className="message-user"><a href={'/users/' + answer.UserId}>{answer.User}</a></p>
                                                                    {answer.Message.split('\n').map((value, index) => {
                                                                        return <p key={index}>{value}</p>;
                                                                    })}
                                                                </div>
                                                                <p className="time">{answer.CreatedAt}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                );
                                            })}
                                        </div>
                                        {answer}
                                    </div>
                                </div>
                            </div>
                            <div className="col-md-4">
                                <div className="card">
                                    <div className="card-header">
                                        Details
                                    </div>
                                    <div className="card-body">
                                        <table className="table table-sm">
                                            <tbody>
                                            <tr>
                                                <td>Benutzer</td>
                                                <td>
                                                    {this.state.data.users.map((user, i) => {
                                                        return (
                                                            <div key={user.UserId} style={user.LeftAt !== null ? {'textDecoration': 'line-through'} : {}}>
                                                                <Row>
                                                                    <Col xs='8'>
                                                                        <a href={'/users/' + user.UserId}>{user.Name}</a>
                                                                    </Col>
                                                                    <Col xs='2'>
                                                                        <OverlayTrigger
                                                                            placement="top"
                                                                            overlay={
                                                                                <Tooltip id='tooltip-info'>
                                                                                    <strong>Beigetreten:</strong><br /> {user.JoinedAt}
                                                                                    {user.LeftAt !== null ? <span>
                                                                                    <br /><strong>Verlassen:</strong><br /> {user.LeftAt}
                                                                                    </span> : null}
                                                                                </Tooltip>
                                                                            }
                                                                        >
                                                                        <Button variant="link" style={{color: "white"}} size="sm"><i className="fas fa-info-circle"></i></Button>
                                                                        </OverlayTrigger>
                                                                    </Col>
                                                                    <Col xs='2'>
                                                                        {(user.UserId != this.state.data.UserId && user.LeftAt == null) ?
                                                                            <Button onClick={this.toggleRemoveUserDialog.bind(this, user.UserId)} variant="link" style={{color: "#d16767"}} size="sm"><i className="fas fa-times-circle"></i></Button>
                                                                        :null}
                                                                    </Col>
                                                                </Row>
                                                            </div>
                                                        );
                                                    })}
                                                    <AddUserDialog />
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>{this.state.data.StateText}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        {closeButton}
                                        <ConfirmDialog
                                            show={this.state.showRemoveUserDialog}
                                            buttonText="entfernen"
                                            buttonVariant="danger"
                                            title="Benutzer entfernen"
                                            text="Möchtest du den Benutzer entfernen?"
                                            onConfirm={this.removeUser.bind(this)}
                                        />

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
