import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";

export default class TicketEntry extends Component {
    constructor() {
        super();
        this.state = {
            data: null,
        };
    }
    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData();
        }
    }

    async loadData() {
        const response = await axios.get('/api/tickets/' + this.props.ticket.Id);

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

        return (
            <>
                <div className="row">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">
                                Chat
                            </div>
                            <div className="card-body">
                                <div className="chat">
                                    {this.state.data.answers.map((answer, i) => {
                                        return (
                                            <div className="message">
                                                <p>{answer.User}</p>
                                                <p>{answer.Message}</p>
                                                <span className="time-right">{answer.CreatedAt}</span>
                                            </div>
                                        );
                                    })}
                                </div>
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
                                            <td>{this.state.data.User}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>{this.state.data.State}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </>
        );
    }
}
