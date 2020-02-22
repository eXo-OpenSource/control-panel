import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import TicketCreate from "./TicketCreate";
import TicketEntry from "./TicketEntry";

export default class Tickets extends Component {
    constructor() {
        super();
        this.state = {
            showCreate: false,
            data: null,
            page: 'overview',
            selectedTicket: null,
        };

        this.showOverview = (refresh) => {
            this.setState({ page: 'overview' });
            if(refresh) {
                this.loadData();
            }
        };

        this.showCreate = () => {
            this.setState({ page: 'create' });
        };

        this.showEntry = (ticket) => {
            this.setState({ page: 'entry', selectedTicket: ticket });
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

        let body = (
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
        );

        let button = <Button onClick={this.showCreate} className="float-right">Ticket erstellen</Button>;

        if(this.state.page === 'create') {
            body = <TicketCreate back={this.showOverview}></TicketCreate>;
            button = <Button onClick={this.showOverview} className="float-right">Zurück</Button>;
        } else if(this.state.page === 'entry') {
            body = <TicketEntry back={this.showOverview} ticket={this.state.selectedTicket}></TicketEntry>;
            button = <Button onClick={this.showOverview} className="float-right">Zurück</Button>;
        }

        return (
            <>
                <div className="row mb-4">
                    <div className="col-md-12">
                        {button}
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        {body}
                    </div>
                </div>
            </>
        );
    }
}

var tickets = document.getElementsByTagName('react-tickets');

for (var index in tickets) {
    const component = tickets[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Tickets {...props} />, component);
    }
}

