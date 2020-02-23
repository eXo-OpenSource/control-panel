import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import {Link} from "react-router-dom";

export default class TicketList extends Component {
    constructor() {
        super();
        this.state = {
            showCreate: false,
            data: null,
            selectedTicket: null,
            state: 'open',
        };
    }

    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData(this.state.state);
        }
    }

    async loadData(state) {
        const response = await axios.get('/api/tickets?state=' + state);

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    async changeState(state) {
        this.setState({
            state: state
        });
        this.loadData(state);
    }

    render() {
        if(this.state.data === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        return (
            <>
                <div className="row mb-4">
                    <div className="col-md-12">
                        <div className="btn-group" role="group" aria-label="Basic example">
                            <Button variant="secondary" className={this.state.state === 'open' ? 'active' : ''} onClick={(evt) => this.changeState('open')}>Offen</Button>
                            <Button variant="secondary" className={this.state.state === 'both' ? 'active' : ''} onClick={(evt) => this.changeState('both')}>Offen / Geschlossen</Button>
                            <Button variant="secondary" className={this.state.state === 'closed' ? 'active' : ''} onClick={(evt) => this.changeState('closed')}>Geschlossen</Button>
                        </div>
                        <Link to="/tickets/create" className="btn btn-primary float-right">Ticket erstellen</Link>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <div className="card">
                            <div className="card-header">
                                Tickets
                            </div>
                            <div className="card-body">
                                <table className="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Benutzer</th>
                                        <th>Kategorie</th>
                                        <th>Titel</th>
                                        <th>Status</th>
                                        <th>Datum</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {this.state.data.map((ticket, i) => {
                                        return <TicketListEntry key={ticket.Id} ticket={ticket} open={this.showEntry}></TicketListEntry>;
                                    })}
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

