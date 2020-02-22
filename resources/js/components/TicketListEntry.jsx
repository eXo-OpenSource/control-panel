import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";

export default class TicketListEntry extends Component {
    constructor() {
        super();

        this.showEntry = () => {
            this.props.open(this.props.ticket);
        };
    }

    render() {
        return (
            <tr>
                <td>{this.props.ticket.User}</td>
                <td>{this.props.ticket.Category}</td>
                <td>{this.props.ticket.Title}</td>
                <td>{this.props.ticket.State}</td>
                <td>{this.props.ticket.CreatedAt}</td>
                <td>
                    <Button size="sm" variant="primary" onClick={this.showEntry}>
                        Details
                    </Button>
                </td>
            </tr>
        );
    }
}

