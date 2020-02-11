import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";

export default class Vehicle extends Component {
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
            if(this.state.data === null) {
                // this.loadData();
            }
        };
    }

    async loadData() {
        const response = await axios.get('/api/histories/' + this.props.historyId);

        try {
            this.setState({
                data: response.data
            });
        } catch (error) {
            console.log(error);
        }
    }

    render() {
        return (
            <>
                <div className="card">
                    <img className="bd-placeholder-img card-img-top"
                         src="https://exo-reallife.de/images/veh/Vehicle_411.jpg"></img>

                    <div className="card-body">
                        <div className="card-title d-flex justify-content-between align-items-start">
                            <h5>{this.props.name}</h5>

                            <button className="btn btn-transparent p-0" type="button">
                                <i className="fas fa-info-circle"></i>
                            </button>
                        </div>
                        <dl className="vehicle-info">
                            <dt>Kilometerstand</dt>
                            <dd>{this.props.distance} km</dd>
                            <dt>Lackfarbe</dt>
                            <dd className="d-flex">
                                <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col1}}></div>
                                <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col2}}></div>
                                <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col3}}></div>
                                <div className="border" style={{height: '25px', width: '25px', backgroundColor: this.props.col4}}></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </>
        );
    }
}

var vehicles = document.getElementsByTagName('react-vehicle');

for (var index in vehicles) {
    const component = vehicles[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<Vehicle {...props} />, component);
    }
}

