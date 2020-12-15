import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import TicketListEntry from "../../tickets/TicketListEntry";
import { Beforeunload } from 'react-beforeunload';
import { Pie, Doughnut } from "react-chartjs-2";
import Button from "react-bootstrap/Button";
import axios from "axios";
import {Form, InputGroup, Modal, Spinner} from "react-bootstrap";
import classNames from 'classnames'
import PollListEntry from "./PollListEntry";
import {Link} from "react-router-dom";

export default class PollList extends Component {
    constructor() {
        super();
        this.state = {
            data: null,
            loading: true,
        };
    }

    async componentDidMount() {
        await this.loadData();
    }

    async loadData()
    {
        try {
            const response = await axios.get('/api/admin/polls');
            this.setState({
                data: response.data === '' ? null : response.data,
                loading: false
            });
        } catch(error) {
            this.setState({
                loading: false
            });
        }
    }

    render() {
        if(this.state.loading === true) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        return (
            <div className="col-12">
                <div className="row mb-2">
                    <div className="col-12">
                        <Link to={'/admin/polls'} className="btn btn-primary float-right">Zurück</Link>
                    </div>
                </div>
                <div className="row">
                    <div className="col-12">
                        <div className="card">
                            <div className="card-header">
                                Abstimmungen
                            </div>
                            <div className="card-body">
                                <table className="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Id</th>
                                        <th>Titel</th>
                                        <th>Ersteller</th>
                                        <th>Datum</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {this.state.data.map((poll, i) => {
                                        return <PollListEntry key={poll.Id} poll={poll}></PollListEntry>;
                                    })}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
