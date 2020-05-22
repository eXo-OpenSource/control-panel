import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import {Link} from "react-router-dom";

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
                <td>{this.props.ticket.Assignee ? this.props.ticket.Assignee : '-'}</td>
                <td>{this.props.ticket.StateText}</td>
                <td>{this.props.ticket.CreatedAt}</td>
                <td>{this.props.ticket.AnswerCount}</td>
                <td>
                    <Link to={'/tickets/' + this.props.ticket.Id} className="btn btn-primary btn-sm">Details</Link>
                </td>
            </tr>
        );
    }
}

