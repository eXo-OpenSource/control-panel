import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import { Button, Modal, Spinner, Form } from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "../tickets/TicketListEntry";
import {Link} from "react-router-dom";
import {element} from "prop-types";
import Row from "react-bootstrap/Row";
import Col from "react-bootstrap/Col";
import InputGroup from "react-bootstrap/InputGroup";
import BanDialog from "./BanDialog";

export default class ScreenCapture extends Component {
    constructor() {
        super();
        this.state = {
            image: ''
        };

        Echo.private(`screencaptures.bruh`)
            .listen('ScreencaptureReceive', this.handleImage.bind(this));
    }

    async handleImage(data) {
        this.setState({
            image: data.image
        });
    }

    async componentDidMount() {
    }

    render() {
        return <div><img src={this.state.image}/></div>;
    }
}

var banDialogs = document.getElementsByTagName('react-screen-capture');

for (var index in banDialogs) {
    const component = banDialogs[index];
    if(typeof component === 'object') {
        const props = Object.assign({}, component.dataset);
        ReactDOM.render(<ScreenCapture {...props} />, component);
    }
}
