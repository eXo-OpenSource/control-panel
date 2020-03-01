import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, Row, Col} from 'react-bootstrap';
import axios from "axios";
import AsyncSelect from 'react-select/async';

export default class AddUserDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };

        this.handleShow = () => {
            this.setState({ show: true });
        };

    }

    onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    handleInputChange(user) {
        this.setState({ user: user });
        return user;
    }

    async loadOptions(inputValue, callback) {
        try{
            const response = await axios.post('/api/users/search', {
                name: inputValue
            });

            let result = [];

            response.data.forEach((value) => {
               result.push({
                   value: value.Id,
                   label: value.Name,
               });
            });

            callback(result);
        } catch(error) {
            console.log(error.response);
        }
    }

    render() {
        return (
            <>
                <Button onClick={this.handleShow.bind(this)} size="sm" variant="secondary">Benutzer hinzufügen</Button>
                <Modal show={this.state.show} onHide={this.handleClose.bind(this)}>
                    <Modal.Header closeButton>
                        <Modal.Title>{this.props.title || "Benutzer auswählen"}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        <AsyncSelect loadOptions={this.loadOptions} onInputChange={this.handleInputChange.bind(this)}
                            className="react-select-container" classNamePrefix="react-select" />
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant="secondary" onClick={this.handleClose.bind(this)}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
            </>
        );
    }
}
