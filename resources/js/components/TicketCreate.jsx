import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";

export default class TicketCreate extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
            data: null
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
        };
    }

    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData();
        }
    }

    async loadData() {
        const response = await axios.get('/api/tickets');

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
                                return <TicketListEntry key={ticket.Id} ticket={ticket}></TicketListEntry>;
                            })}
                            </tbody>
                        </table>
                    </div>
                </div>
            </>
        );
    }
}
