import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import TicketListEntry from "../../tickets/TicketListEntry";
import { Beforeunload } from 'react-beforeunload';
import { Pie, Doughnut } from "react-chartjs-2";
import Button from "react-bootstrap/Button";
import axios from "axios";
import {Form, InputGroup, Modal, Spinner} from "react-bootstrap";
import classNames from 'classnames'
import {Link} from "react-router-dom";

export default class PollListEntry extends Component {
    render() {
        return (
            <tr>
                <td>{this.props.poll.Id}</td>
                <td>{this.props.poll.Title}</td>
                <td>{this.props.poll.Admin}</td>
                <td>{this.props.poll.CreatedAt}</td>
                <td>
                    <Link to={'/admin/polls/' + this.props.poll.Id} className="btn btn-primary btn-sm">Details</Link>
                </td>
            </tr>
        );
    }
}
