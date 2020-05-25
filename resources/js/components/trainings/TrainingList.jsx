import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import TrainingListEntry from "./TrainingListEntry";
import {Link} from "react-router-dom";

export default class TrainingList extends Component {
    constructor() {
        super();
        this.state = {
            showCreate: false,
            data: null,
            selectedTicket: null,
            errorMessage: null,
            state: 'progress',
        };
    }

    async componentDidMount() {
        if(this.state.data === null) {
            this.loadData(this.state.state);
        }
    }

    async loadData(state) {
        try {
            const response = await axios.get('/api/trainings?state=' + state);
            this.setState({
                data: response.data
            });
        } catch (error) {
            if(error.message === 'Request failed with status code 403') {
                this.setState({
                    errorMessage: 'Zugriff verweigert'
                });
            } else {
                this.setState({
                    errorMessage: 'Unbekannter Fehler: ' . error.message
                });
                throw error;
            }
        }
    }

    async changeState(state) {
        this.setState({
            state: state
        });
        this.loadData(state);
    }

    render() {
        if(this.state.errorMessage !== null) {
            return <div className="text-center"><h4 className="pt-3">{this.state.errorMessage}</h4></div>;
        }

        if(this.state.data === null) {
            return <div className="text-center"><Spinner animation="border"/></div>;
        }

        return (
            <>
                <div className="row mb-4">
                    <div className="col-md-12">
                        <div className="btn-group" role="group">
                            <Button variant="secondary" className={this.state.state === 'progress' ? 'active' : ''} onClick={(evt) => this.changeState('progress')}>Offen</Button>
                            <Button variant="secondary" className={this.state.state === 'both' ? 'active' : ''} onClick={(evt) => this.changeState('both')}>Offen / Abgeschlossen</Button>
                            <Button variant="secondary" className={this.state.state === 'finished' ? 'active' : ''} onClick={(evt) => this.changeState('finished')}>Abgeschlossen</Button>
                        </div>
                    </div>
                </div>
                <div className="row">
                    <div className="col-md-12">
                        <div className="card">
                            <div className="card-header">
                                Übungen
                            </div>
                            <div className="card-body">
                                <table className="table table-sm">
                                    <thead>
                                    <tr>
                                        <th>Ausbilder</th>
                                        <th>Teilnehmer</th>
                                        <th>Übung</th>
                                        <th>Status</th>
                                        <th>Datum</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    {this.state.data.map((training, i) => {
                                        return <TrainingListEntry key={training.Id} training={training} open={this.showEntry}></TrainingListEntry>;
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

