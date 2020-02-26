import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup} from 'react-bootstrap';
import axios from "axios";

export default class ConfirmDialog extends Component {
    constructor() {
        super();
        this.state = {
            show: false,
        };

        this.handleClose = () => {
            this.setState({ show: false });
        };
    }

    componentDidUpdate(prevProps) {
        if (this.props.show != prevProps.show) {
            this.setState({show: this.props.show})
        }
    }

    async confirm() {
        if (this.props.onConfirm) {
            this.props.onConfirm();
        }
        this.handleClose();
    }

    render() {
        return (
            <>
                <Modal show={this.state.show}>
                    <Modal.Header closeButton>
                        <Modal.Title>{this.props.title || "Bestätigen"}</Modal.Title>
                    </Modal.Header>
                    <Modal.Body>
                        {this.props.text || "Möchtest du die Aktion ausführen?"}
                    </Modal.Body>
                    <Modal.Footer>
                        <Button variant={this.props.buttonVariant || 'primary'} onClick={this.confirm.bind(this)}>
                            Bestätigen
                        </Button>
                        <Button variant="secondary" onClick={this.handleClose}>
                            Schließen
                        </Button>
                    </Modal.Footer>
                </Modal>
            </>
        );
    }
}


