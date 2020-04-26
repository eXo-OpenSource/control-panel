import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import {Link} from "react-router-dom";

export default class TrainingListEntry extends Component {
    constructor() {
        super();

        this.showEntry = () => {

            this.props.open(this.props.training);
        };
    }

    render() {
        return (
            <tr>
                <td>{this.props.training.User}</td>
                <td>{this.props.training.Name}</td>
                <td>{this.props.training.StateText}</td>
                <td>{this.props.training.CreatedAt}</td>
                <td>
                    <Link to={'/trainings/' + this.props.training.Id} className="btn btn-primary btn-sm">Details</Link>
                </td>
            </tr>
        );
    }
}

