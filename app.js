var Page = React.createClass({
    getInitialState: function() {
        return {display: 'table'};
    },
    changeDisplay: function(display) {
        this.setState({display: display});
    },
    render: function() {
        return (
            <div className="container">
                <ul className="nav nav-pills">
                    <li role="display" onClick={this.changeDisplay.bind(this, 'table')} className={'table' == this.state.display ? 'active' : ''}><a href="#">Table</a></li>
                    <li role="display" onClick={this.changeDisplay.bind(this, 'blocks')} className={'blocks' == this.state.display ? 'active' : ''}><a href="#">Blocks</a></li>
                </ul>
                <CoverList url="resources.json" display={this.state.display} />
            </div>
        );
    }
});

var CoverList = React.createClass({
    loadData: function() {
        $.ajax({
            url: this.props.url,
            dataType: 'json',
            success: function(data) {
                this.setState({resources: data});
            }.bind(this),
            error: function(xhr, status, err) {
                console.error(this.props.url, status, err.toString());
            }.bind(this)
        });
    },
    componentDidMount: function() {
        this.loadData();
    },
    getInitialState: function() {
        return {resources: []};
    },
    render: function() {
        if ('blocks' == this.props.display) {
            var covers = this.state.resources.map(function (cover) {
                return <Cover key={cover.uuid} source={cover.source} title={cover.title} image={cover.image} url={cover.url} />
            });

            return (
                <div>{covers}</div>
            );
        }

        var rows = this.state.resources.map(function (cover) {
            return <CoverAsTableRow key={cover.uuid} source={cover.source} title={cover.title} image={cover.image} url={cover.url} />
        });

        return (
            <table className="table table-condensed table-striped">{rows}</table>
        );
    }
});

var Cover = React.createClass({
    render: function() {
        var style = {
            backgroundImage: 'url(' + this.props.image + ')'
        };
        return (
            <a href={this.props.url}>
                <div className="cover" style={style}>
                    <div className="title">{this.props.title}</div>
                    <div className="source">{this.props.source}</div>
                </div>
            </a>
        );
    }
});

var CoverAsTableRow = React.createClass({
    render: function() {
        return (
            <tr>
                <td>
                    <a href={this.props.url}>{this.props.title}</a>
                </td>
            </tr>
        );
    }
});

React.render(
    <Page />,
    document.getElementById('a')
);
