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

export default class PollEntry extends Component {
    constructor({match}) {
        super();
        this.state = {
            pollId: match.params.pollId,
            agree: 0,
            disagree: 0,
            data: null,
            loading: true,
            submitting: false,
            chartData: {},
            chartOptions: {'maintainAspectRatio': false}
        };
    }

    async componentDidMount() {
        await this.loadData();
    }

    async loadData()
    {
        try {
            const response = await axios.get('/api/admin/polls/' + this.state.pollId);
            this.setState({
                data: response.data === '' ? null : response.data,
                loading: false
            });
            this.calculateChartData();
        } catch(error) {
            this.setState({
                loading: false
            });
        }
    }

    async calculateChartData()
    {
        if(this.state.data === null)
            return;

        let agree = 0;
        let disagree = 0;

        this.state.data.votes.forEach((vote) => {
           if(vote.Vote === 0) {
               disagree++;
           } else {
               agree++;
           }
        });

        this.setState({
           chartData: {
               'datasets': [
                   {
                       'data': [agree, disagree],
                       'backgroundColor': ['rgba(69, 161, 100, 1)', 'rgba(209, 103, 103, 1)'],
                       'borderWidth': 0
                   }
               ],
               'labels': ['Dafür', 'Dagegen']
           },
            agree: agree,
            disagree: disagree
        });
    }

    render() {
        if(this.state.loading === true) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        return (
            <div className="col-12">
                <div className="row mb-2">
                    <div className="col-12">
                        <Link to={'/admin/polls/history'} className="btn btn-primary float-right">Zurück</Link>
                    </div>
                </div>
                <div className="row">
                    <div className="col-6">
                        <h2>{this.state.data.Title}</h2>
                        {this.state.data.URL ? <p><a href={this.state.data.URL}>{this.state.data.URL}</a></p> : ''}

                        <div style={{'height': '40vh', 'width': '40vh'}}>
                            <Pie data={this.state.chartData} options={this.state.chartOptions} />
                        </div>
                    </div>
                    <div className="col-6">
                        <div className="row">
                            <div className="col-12">
                                <h3>Info</h3>
                                <span className="d-block">Ersteller: {this.state.data.Admin}</span>
                                <span className="d-block">Datum: {this.state.data.CreatedAt}</span>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-12 mt-4">
                                <h3>Ergebnis</h3>
                                <span className="d-block">Dafür: {this.state.agree}</span>
                                <span className="d-block">Dagegen: {this.state.disagree}</span>
                                <span className="d-block">Gesamt: {this.state.agree + this.state.disagree}</span>
                            </div>
                        </div>
                        <div className="row">
                            <div className="col-12 mt-4">
                                <h3>Stimmen</h3>
                                {this.state.data.votes.map((vote) => {
                                    return <span className="d-block" key={vote.AdminId}>
                                        {vote.Admin} <span className={classNames('badge', vote.Vote === 1 ? 'badge-success' : 'badge-danger')}>{vote.Vote === 1 ? 'Dafür' : 'Dagegen'}</span>
                                    </span>;
                                })}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}
