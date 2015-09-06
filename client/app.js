var Button = ReactBootstrap.Button;
var ButtonInput = ReactBootstrap.ButtonInput;
var Glyphicon = ReactBootstrap.Glyphicon;
var Input = ReactBootstrap.Input;
var Nav = ReactBootstrap.Nav;
var NavItem = ReactBootstrap.NavItem;
var Table = ReactBootstrap.Table;

var Page = React.createClass({
    getInitialState: function() {
        return {
            resources: [],
            display: 'table'
        };
    },
    componentDidMount: function() {
        this.loadData();
        setInterval(this.loadData, this.props.pollInterval);
    },
    pollPause: false,
    isPollingOnPause: function() {
        return this.pollPause;
    },
    pausePolling: function() {
        this.pollPause = true;
        console.log('pause polling');
    },
    resumePolling: function() {
        this.pollPause = false;
        console.log('resume polling');
    },
    setPolling: function() {
        // pause polling if all resources are completed
        var incomplete = this.state.resources.filter(function (bookmark) {
            return bookmark.state == 'incomplete';
        });
        if (incomplete.length > 0) {
            this.resumePolling();
        } else {
            this.pausePolling();
        }
    },
    loadData: function() {
        if (this.isPollingOnPause()) {
            return;
        }
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            cache: false,
            success: function(data) {
                this.setState({
                    resources: data,
                    display: this.state.display
                });
                this.setPolling();
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    changeDisplay: function(display) {
        this.setState({
            resources: this.state.resources,
            display: display
        });
    },
    handleAddBookmarkSubmit: function(bookmark) {
        $.ajax({
            url: this.props.url,
            dataType: "json",
            type: "POST",
            data: bookmark,
            success: function(data) {
                this.addBookmark(data);
                this.resumePolling();
            }.bind(this),
            error: function(xhr, status, err) {
                console.error("Error when adding bookmark", status, err.toString());
            }.bind(this)
        });
    },
    handleBookmarkDelete: function(bookmarkUuid) {
        $.ajax({
            url: this.props.url + bookmarkUuid + "/delete",
            dataType: "json",
            type: "POST",
            success: function(data) {
                this.removeBookmark(bookmarkUuid);
            }.bind(this),
            error: function(xhr, status, err) {
                console.error("Error when deleting bookmark", status, err.toString());
            }.bind(this)
        });
    },
    addBookmark: function(bookmark) {
        this.setState({
            resources: this.state.resources.concat(bookmark),
            display: this.state.display
        });
    },
    removeBookmark: function(bookmarkUuid) {
        var bookmarks = this.state.resources;
        bookmarks = bookmarks.filter(function (bookmark) {
            return bookmark.uuid != bookmarkUuid;
        });
        this.setState({
            resources: bookmarks,
            display: this.state.display
        });
    },
    render: function() {
        return (
            <div className="container" style={{marginTop: "1em"}}>
                <Nav bsStyle='pills' activeKey={this.state.display} onSelect={this.changeDisplay}>
                    <NavItem eventKey={'table'} href="#"><Glyphicon glyph="th-list" /></NavItem>
                    <NavItem eventKey={'blocks'} href="#"><Glyphicon glyph="th" /></NavItem>
                    <NavItem eventKey={'stack'} href="#"><Glyphicon glyph="menu-hamburger" /></NavItem>
                    <NavItem eventKey={'slides'} href="#"><Glyphicon glyph="picture" /></NavItem>
                    <NavItem eventKey={'video'} href="#"><Glyphicon glyph="film" /></NavItem>
                </Nav>
                <AddBookmarkForm onAddBookmarkSubmit={this.handleAddBookmarkSubmit} />
                <div style={{"marginTop": "1em"}}>
                    <BookmarkList resources={this.state.resources} display={this.state.display} onBookmarkDelete={this.handleBookmarkDelete} />
                </div>
            </div>
        );
    }
});

var AddBookmarkForm = React.createClass({
    handleSubmit: function(e) {
        e.preventDefault();

        var url = this.refs.url.getValue().trim();
        if (!url) {
            return;
        }

        this.props.onAddBookmarkSubmit({url: url});

        this.refs.url.getInputDOMNode().value = "";
        return;
    },
    render: function() {
        return (
            <form onSubmit={this.handleSubmit}>
                <Input type="text" placeholder="Enter an URL to bookmark" ref="url" />
                <ButtonInput type="submit" value="Add" />
            </form>
        );
    }
});

var BookmarkList = React.createClass({
    render: function() {
        if ('blocks' == this.props.display) {
            var bookmarks = this.props.resources.map(function (bookmark) {
                return <Bookmark key={bookmark.uuid} type={bookmark.type} title={bookmark.title} image={bookmark.image} url={bookmark.url} />
            });

            return (
                <div>{bookmarks}</div>
            );
        }

        if ('stack' == this.props.display) {
            var bookmarks = this.props.resources.map(function (bookmark) {
                return <BookmarkAsStack key={bookmark.uuid} type={bookmark.type} title={bookmark.title} image={bookmark.image} url={bookmark.url} description={bookmark.description} />
            });

            return (
                <div>{bookmarks}</div>
            );
        }

        if ('slides' == this.props.display) {
            return (
                <BookmarkSlider resources={this.props.resources} />
            );
        }

        if ('video' == this.props.display) {
            return (
                <BookmarkVideoPlaylist resources={this.props.resources} />
            );
        }

        var rows = this.props.resources.map(function (bookmark) {
            return <BookmarkAsTableRow key={bookmark.uuid} state={bookmark.state} type={bookmark.type} title={bookmark.title} image={bookmark.image} url={bookmark.url} onBookmarkDelete={this.props.onBookmarkDelete.bind(null, bookmark.uuid)}/>
        }, this);

        return (
            <Table striped bordered condensed><tbody>{rows}</tbody></Table>
        );
    }
});

var BookmarkSlider = React.createClass({
    getInitialState: function() {
        return {
            current: 0
        };
    },
    handleClickPrev: function() {
        var newCurrent = this.state.current - 1;
        if (newCurrent < 0) {
            newCurrent = this.props.resources.length - 1;
        }
        this.setState({
            current: newCurrent
        });
    },
    handleClickNext: function() {
        var newCurrent = this.state.current + 1;
        if (newCurrent >= this.props.resources.length) {
            newCurrent = 0;
        }
        this.setState({
            current: newCurrent
        });
    },
    render: function() {
        var bookmarks = this.props.resources.map(function (bookmark, index) {
            var isVisible = (index == this.state.current);

            return <BookmarkAsSlide isVisible={isVisible} key={bookmark.uuid} type={bookmark.type} title={bookmark.title} image={bookmark.image} url={bookmark.url} description={bookmark.description} />
        }.bind(this));

        return (
            <div>
                <div>
                    <Button onClick={this.handleClickPrev}>Prev</Button>
                    <Button onClick={this.handleClickNext}>Next</Button>
                </div>
                <div className="slides-container">
                    {bookmarks}
                </div>
            </div>
        );
    }
});

var BookmarkVideoPlaylist = React.createClass({
    getInitialState: function() {
        return {
            current: 0
        };
    },
    handleClickPrev: function() {
        var newCurrent = this.state.current - 1;
        if (newCurrent < 0) {
            newCurrent = this.props.resources.length - 1;
        }
        this.setState({
            current: newCurrent
        });
    },
    handleClickNext: function() {
        var newCurrent = this.state.current + 1;
        if (newCurrent >= this.props.resources.length) {
            newCurrent = 0;
        }
        this.setState({
            current: newCurrent
        });
    },
    render: function() {
        var bookmarks = this.props.resources
            .filter(function (bookmark, index) {
                return index == this.state.current;
            }.bind(this))
            .map(function (bookmark, index) {
                return <BookmarkAsVideoPlaylistItem key={bookmark.uuid} title={bookmark.title} url={bookmark.url} description={bookmark.description} video={bookmark.video} />
            }.bind(this));

        return (
            <div>
                <div>
                    <Button onClick={this.handleClickPrev}>Prev</Button>
                    <Button onClick={this.handleClickNext}>Next</Button>
                </div>
                <div className="slides-container">
                    {bookmarks}
                </div>
            </div>
        );
    }
});

var Bookmark = React.createClass({
    render: function() {
        var image = this.props.image || 'loader.png';
        var style = {
            backgroundImage: 'url(' + image + ')',
            backgroundSize: 'cover'
        };
        return (
            <Link href={this.props.url}>
                <div className="bookmark" style={style}>
                    <div className="title">{this.props.title}</div>
                    <div className="type">{this.props.type}</div>
                </div>
            </Link>
        );
    }
});

var BookmarkAsStack = React.createClass({
    render: function() {
        var image = this.props.image || 'loader.png';
        var style = {
            backgroundImage: 'url(' + image + ')',
            backgroundSize: 'cover'
        };
        return (
            <div className="row">
                <div className="col-md-2">
                    <Link href={this.props.url}>
                        <div className="bookmark" style={style}></div>
                    </Link>
                </div>
                <div className="col-md-10">
                    <h2>{this.props.title}</h2>
                    <p>{this.props.type}</p>
                    <p>{this.props.description}</p>
                </div>
            </div>
        );
    }
});

var BookmarkAsSlide = React.createClass({
    render: function() {
        var image = this.props.image || 'loader.png';
        var className = this.props.isVisible ? 'visible' : 'invisible';
        return (
            <img src={image} className={className} />
        );
    }
});

var BookmarkAsVideoPlaylistItem = React.createClass({
    render: function() {
        return (
            <iframe src={this.props.video} width="500" height="300" frameBorder="0" />
        );
    }
});

var BookmarkAsTableRow = React.createClass({
    render: function() {
        return (
            <tr>
                <td>
                    <Link href={this.props.url}>{this.props.title}</Link>
                </td>
                <td>
                    {this.props.type}
                </td>
                <td>
                    {this.props.state}
                </td>
                <td>
                    <Button bsStyle="danger" bsSize="xsmall" onClick={this.props.onBookmarkDelete}>Delete</Button>
                </td>
            </tr>
        );
    }
});

var Link = React.createClass({
    render: function() {
        return (
            <a href={this.props.href} target="_blank">{this.props.children}</a>
        );
    }
});

React.render(
    <Page url="http://localhost:8080/" pollInterval={2000}/>,
    document.getElementById('a')
);
