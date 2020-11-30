import React, { Component, useState } from 'react';
import ReactDOM from 'react-dom';
import {Button, Modal, Spinner, Form, InputGroup, OverlayTrigger, Tooltip, Row, Col} from 'react-bootstrap';
import axios from "axios";
import TicketListEntry from "./TicketListEntry";
import SelectUserDialog from '../helpers/SelectUserDialog';
import {
    Link,
    useParams
} from "react-router-dom";
import ConfirmDialog from '../helpers/ConfirmDialog';
import SelectUserFromListDialog from "../helpers/SelectUserFromListDialog";
import { ToastContainer, toast } from 'react-toastify';
import Linkify from 'react-linkify';
import 'react-toastify/dist/ReactToastify.css';

export default class TicketEntryThread extends Component {
    constructor(props) {
        super(props);
        this.state = {
            message: ''
        };
    }

    async onChange(e) {
        this.setState({ [e.target.name]: e.target.value });
    }

    async send() {
        if (this.props.sendMessage(this.state.message)) {
            await this.setState({
                message: ''
            });
        }
    }

    render() {
        let answer = this.props.canAnswer ? (
            <Form>
                <Form.Group>
                    <Form.Label>Nachricht</Form.Label>
                    <InputGroup>
                        <Form.Control disabled={this.props.submitting} as="textarea" rows="3" name="message" placeholder="Nachricht" value={this.state.message} onChange={this.onChange.bind(this)} />
                    </InputGroup>
                </Form.Group>

                <Button disabled={this.props.submitting} onClick={this.send.bind(this)} className="float-right">Senden</Button>
            </Form>
        ) : '';

        return (
            <div className="card thread">
                <div className="card-body">
                    {this.props.answers.map((answer, i) => {
                        if(parseInt(answer.MessageType) === 1) {
                            return (
                                <div>
                                    <div key={answer.Id} className="thread-message">
                                        <div className="message">
                                            <div className="message-content">
                                                <span className="message-header">
                                                    <span className="message-user">
                                                        Systemnachricht
                                                    </span>
                                                    <span className="spacer">·</span>
                                                    <span className="message-time">
                                                        {answer.CreatedAt}
                                                    </span>
                                                </span>
                                                <Linkify>
                                                    {answer.Message.split('\n').map((value, index) => {
                                                        return <p key={index}>{value}</p>;
                                                    })}
                                                </Linkify>
                                            </div>
                                        </div>
                                    </div>
                                    <hr />
                                </div>
                            );
                        }

                        return (
                            <div>
                                <div key={answer.Id} className="thread-message">
                                    <div className={answer.IsAdmin ? 'message message-admin' : 'message'}>
                                        <div className="message-content">
                                            <span className="message-header">
                                                <span className="message-user">
                                                    {this.props.minimal == true &&
                                                    answer.User
                                                    ||
                                                    <a href={'/users/' + answer.UserId}>{answer.User}</a>
                                                    }
                                                </span>
                                                <span className="spacer">·</span>
                                                <span className="message-time">
                                                    {answer.CreatedAt}
                                                </span>
                                            </span>
                                            <Linkify>
                                                {answer.Message !== null ? answer.Message.split('\n').map((value, index) => {
                                                    return <p key={index}>{value}</p>;
                                                }) : <p className="font-italic">Keine Nachricht</p>}
                                            </Linkify>
                                        </div>
                                    </div>
                                </div>
                                <hr />
                            </div>
                        );
                    })}
                    {answer}
                </div>
            </div>);
    }
}
