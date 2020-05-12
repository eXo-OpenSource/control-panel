import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";

export default class ChargeForm extends Component {
    constructor() {
        super();
        this.state = {
            dollars: 0,
        };
    }

    calculate() {
        var dollars = 0;
        if (Number(this.state.amount) > 0 ) {
            dollars = Number(this.state.amount);
        }
        if (this.props.paymenttype == "paysafecard") {
            dollars = Math.floor(this.state.amount * 0.9);
        }

        this.setState({dollars: dollars})
    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value }, () => this.calculate());
    }

    render() {
        return (
            <div>
                <div className="row mb-2">
                    <div className="col-3 font-weight-bold">Gewünschter Betrag:</div>
                    <div className="col-3">
                        <div className="input-group">
                            <input v-model="amount" name="amount"  className="form-control" type="number" onChange={this.onChange.bind(this)}/>
                            <div className="input-group-append">
                                <span className="input-group-text" id="basic-addon2">€</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div className="row mb-2">
                    <div className="col-3 font-weight-bold">Du erhälst:</div>
                    <div className="col">{this.state.dollars} eXo Dollar
                        {this.props.paymenttype == 'paysafecard' ?
                            <span className="text-danger">< br/>(aufgrund von 10% Bearbeitungsgebühren bei paysafecard)</span>
                        : null}
                    </div>
                </div>
            </div>
        );
    }
}

var chargeForms = document.getElementsByTagName('react-charge-form');

for (var index in chargeForms) {
    const component = chargeForms[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<ChargeForm {...props} />, component);
    }
}

