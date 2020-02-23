import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import {
    Link,
    useParams
} from "react-router-dom";

export default class TicketEntry extends Component {
    constructor({match}) {
        super();
        this.state = {
            data: null,
            message: '',
            ticketId: match.params.ticketId,
        };
    }
    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData();
        }
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
                                                                        <pre>{answer.Message}</pre>
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
                                                                    <p><a href={'/users/' + answer.UserId}>{answer.User}</a></p>
                                                                    <pre>{answer.Message}</pre>
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
                                                            <p key={user.UserId} style={user.LeftAt !== null ? {'textDecoration': 'line-through'} : {}}>
                                                                <a href={'/users/' + user.UserId}>{user.Name}</a>
                                                            </p>
                                                        );
                                                    })}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Status</td>
                                                <td>{this.state.data.StateText}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        {closeButton}
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
