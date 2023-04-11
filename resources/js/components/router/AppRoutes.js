import { createRouter, createWebHistory } from "vue-router";
import Homepage from "./../homepage/Page.vue";
import About from "./../administrator/About.vue";
import Logout from "./../components/Logout.vue";
import Error404 from "./Error404.vue";
import Auth from "./../auth/Page.vue";

import AdminLayoutVue from "./../administrator/Page.vue";
import DashboardPage from "./../administrator/dashboard/Page.vue";

import UnitPage from "./../administrator/dashboard/unit/Page.vue";
import UnitSectionUnitEventListPage from "./../administrator/dashboard/unit/section/unit_event_list/Page.vue";
import UnitSectionUnitTicketInventoryPage from "./../administrator/dashboard/unit/section/unit_ticket_inventory/Page.vue";
import UnitSectionUnitReturnedBagPage from "./../administrator/dashboard/unit/section/unit_return_bag/Page.vue";
import UnitTicketsTable from "./../administrator/dashboard/unit/section/unit_ticket_inventory/components/Table.vue";
import UnitEventSection from "./../administrator/dashboard/unit/section/unit_ticket_inventory/components/sections/Event.vue";
import UnitBagSection from "./../administrator/dashboard/unit/section/unit_ticket_inventory/components/sections/Bags.vue";

import EventPage from "./../administrator/dashboard/event/Page.vue";
import EventInventoryPage from "./../administrator/dashboard/event/event_inventory/Page.vue";
import OfflineEventInventoryPage from "./../administrator/dashboard/event/event_inventory/sections/Offline.vue";
import ShowBags from "./../administrator/dashboard/event/event_inventory/sections/Bags.vue";
import EventBags from "./../administrator/dashboard/event/event_bags/Page.vue";
import EventSellers from "./../administrator/dashboard/event/event_sellers/Page.vue";
import ReturnedBagsPage from "./../administrator/dashboard/event/return/Page.vue";
import ReturnedBagsTable from "./../administrator/dashboard/event/return/components/Table.vue";
import ReturnedAllTickets from "./../administrator/dashboard/event/return/components/AllTickets.vue";
import EventHistory from "./../administrator/dashboard/event/event_history/Page.vue";

import DailySellerBreakdown from "./../administrator/dashboard/event/daily_breakdown/Page.vue";

import EventLogs from "./../administrator/dashboard/event/logs/Page.vue";
import EventAllBags from "./../administrator/dashboard/event/event_bags/sections/all/Page.vue";
import EventActiveBags from "./../administrator/dashboard/event/event_bags/sections/active/Page.vue";
import EventNotActiveBags from "./../administrator/dashboard/event/event_bags/sections/not_active/Page.vue";

import TicketsInsideBag from "./../administrator/dashboard/event/inside_bag/Page.vue";
import TicketsInsideBagAll from "./../administrator/dashboard/event/inside_bag/sections/all_tickets/Page.vue";
import TicketsInsideBagAllOffline from "./../administrator/dashboard/event/inside_bag/sections/all_tickets/components/Offline.vue";
import TicketsInsideBagAllOffline2 from "./../administrator/dashboard/event/inside_bag/sections/all_tickets/components/Offline2.vue";
import TicketsInsideBagSold from "./../administrator/dashboard/event/inside_bag/sections/sold_tickets/Page.vue";
import TicketsInsideBagUnsold from "./../administrator/dashboard/event/inside_bag/sections/unsold_tickets/Page.vue";

import AccountPage from "./../administrator/account/Page.vue";
import SettlementPage from "./../administrator/settlement/Page.vue";
import TransactionPage from "./../administrator/transaction/Page.vue";

import ClientUnits from "./../client/unit/Page.vue";
import ClientUnitsEventList from "./../client/unit/section/unit_event_list/Page.vue";
import ClientUnitsTicketInventory from "./../client/unit/section/unit_ticket_inventory/Page.vue";
import ClientUnitTicketsTable from "./../client/unit/section/unit_ticket_inventory/components/Table.vue";
import ClientUnitEventSection from "./../client/unit/section/unit_ticket_inventory/components/sections/Event.vue";
import ClientUnitBagSection from "./../client/unit/section/unit_ticket_inventory/components/sections/Bags.vue";

import ClientEventPage from "./../client/event/Page.vue";
import ClientEventInventoryPage from "./../client/event/event_inventory/Page.vue";
import ClientOfflineEventInventoryPage from "./../client/event/event_inventory/sections/Offline.vue";
import ClientShowBags from "./../client/event/event_inventory/sections/Bags.vue";
import ClientEventBags from "./../client/event/event_bags/Page.vue";
import ClientEventSellers from "./../client/event/event_sellers/Page.vue";
import ClientReturnedBagsPage from "./../client/event/return/Page.vue";
import ClientReturnedBagsTable from "./../client/event/return/components/Table.vue";
import ClientReturnedAllTickets from "./../client/event/return/components/AllTickets.vue";
import ClientEventHistory from "./../client/event/event_history/Page.vue";

import ClientDailySellerBreakdown from "./../client/event/daily_breakdown/Page.vue";

import ClientEventLogs from "./../client/event/logs/Page.vue";
import ClientEventAllBags from "./../client/event/event_bags/sections/all/Page.vue";
import ClientEventActiveBags from "./../client/event/event_bags/sections/active/Page.vue";
import ClientEventNotActiveBags from "./../client/event/event_bags/sections/not_active/Page.vue";

import ClientTicketsInsideBag from "./../client/event/inside_bag/Page.vue";
import ClientTicketsInsideBagAll from "./../client/event/inside_bag/sections/all_tickets/Page.vue";
import ClientTicketsInsideBagAllOffline from "./../client/event/inside_bag/sections/all_tickets/components/Offline.vue";
import ClientTicketsInsideBagAllOffline2 from "./../client/event/inside_bag/sections/all_tickets/components/Offline2.vue";
import ClientTicketsInsideBagSold from "./../client/event/inside_bag/sections/sold_tickets/Page.vue";
import ClientTicketsInsideBagUnsold from "./../client/event/inside_bag/sections/unsold_tickets/Page.vue";

const routes = [
    { path: "/:catchAll(.*)", component: Error404 },
    { path: "/", component: Homepage },
    // { path: "/about", component: About },
    { path: "/auth", component: Auth },
    { path: "/administrator/logout", component: Logout },
    {
        path: "/administrator/dashboard",
        component: AdminLayoutVue,
        children: [
            { path: "/administrator/dashboard", component: DashboardPage },
            { path: "/administrator/transaction", component: TransactionPage },
            { path: "/administrator/settlement", component: SettlementPage },
            { path: "/administrator/accounts", component: AccountPage },

            {
                path: "/administrator/dashboard",
                component: UnitPage,
                children: [
                    {
                        path: "/administrator/dashboard/:id/event_list",
                        component: UnitSectionUnitEventListPage,
                    },
                    {
                        path: "/administrator/dashboard/:id/unit_ticket_inventory",
                        component: UnitSectionUnitTicketInventoryPage,
                        children: [
                            {
                                path: "/administrator/dashboard/:id/unit_ticket_inventory",
                                component: UnitTicketsTable,
                            },
                            {
                                path: "/administrator/dashboard/:id/unit_ticket_inventory/events",
                                component: UnitEventSection,
                            },
                            {
                                path: "/administrator/dashboard/:id/unit_ticket_inventory/bags",
                                component: UnitBagSection,
                            },
                        ],
                    },
                    {
                        path: "/administrator/dashboard/:id/unit_returned_bag",
                        component: UnitSectionUnitReturnedBagPage,
                    },
                ],
            },
            {
                path: "/administrator/dashboard/:id/:id",
                component: EventPage,
                children: [
                    {
                        path: "/administrator/dashboard/:id/:id/logs",
                        component: EventLogs,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_inventory",
                        component: EventInventoryPage,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_inventory/offline",
                        component: OfflineEventInventoryPage,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/show_bags",
                        component: ShowBags,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_bags",
                        component: EventBags,
                        children: [
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/all_bags",
                                component: EventAllBags,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/active_bags",
                                component: EventActiveBags,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/not_active_bags",
                                component: EventNotActiveBags,
                            },
                        ],
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_bags/inside_bag",
                        component: TicketsInsideBag,
                        children: [
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/all_tickets/:id",
                                component: TicketsInsideBagAll,
                            },

                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/sold_tickets/:id",
                                component: TicketsInsideBagSold,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/unsold_tickets/:id",
                                component: TicketsInsideBagUnsold,
                            },
                        ],
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/all_tickets/offline",
                        component: TicketsInsideBagAllOffline,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_bags/inside_bag/all_tickets/offline2",
                        component: TicketsInsideBagAllOffline2,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_sellers",
                        component: EventSellers,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/returned_bags",
                        component: ReturnedBagsPage,
                        children: [
                            {
                                path: "/administrator/dashboard/:id/:id/returned_bags",
                                component: ReturnedBagsTable,
                            },
                            {
                                path: "/administrator/dashboard/:id/:id/returned_bags/inside_bag/all_tickets",
                                component: ReturnedAllTickets,
                            },
                        ],
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/daily_breakdown",
                        component: DailySellerBreakdown,
                    },
                    {
                        path: "/administrator/dashboard/:id/:id/event_history",
                        component: EventHistory,
                    },
                ],
            },
        ],
    },
    {
        path: "/client/branch",
        component: ClientUnits,
        children: [
            {
                path: "/client/branch/:unitid/event_list",
                component: ClientUnitsEventList,
            },
            {
                path: "/client/branch/:unitid/unit_ticket_inventory",
                component: ClientUnitsTicketInventory,
                children: [
                    {
                        path: "/client/branch/:unitid/unit_ticket_inventory",
                        component: ClientUnitTicketsTable,
                    },
                    {
                        path: "/client/branch/:unitid/unit_ticket_inventory/events",
                        component: ClientUnitEventSection,
                    },
                    {
                        path: "/client/branch/:unitid/unit_ticket_inventory/bags",
                        component: ClientUnitBagSection,
                    },
                ],
            },
        ],
    },
    {
        path: "/client/branch/:unitid/:eventid",
        component: ClientEventPage,
        children: [
            {
                path: "/client/branch/:unitid/:eventid/logs",
                component: ClientEventLogs,
            },
            {
                path: "/client/branch/:unitid/:eventid/event_inventory",
                component: ClientEventInventoryPage,
            },
            {
                path: "/client/branch/:unitid/:eventid/event_inventory/offline",
                component: ClientOfflineEventInventoryPage,
            },
            {
                path: "/client/branch/:unitid/:eventid/show_bags",
                component: ClientShowBags,
            },
            {
                path: "/client/branch/:unitid/:eventid/event_bags",
                component: ClientEventBags,
                children: [
                    {
                        path: "/client/branch/:unitid/:eventid/event_bags/all_bags",
                        component: ClientEventAllBags,
                    },
                    {
                        path: "/client/branch/:unitid/:eventid/event_bags/active_bags",
                        component: ClientEventActiveBags,
                    },
                    {
                        path: "/client/branch/:unitid/:eventid/event_bags/not_active_bags",
                        component: ClientEventNotActiveBags,
                    },
                ],
            },
            {
                path: "/client/branch/:unitid/:eventid/event_bags/inside_bag",
                component: ClientTicketsInsideBag,
                children: [
                    {
                        path: "/client/branch/:unitid/:eventid/event_bags/inside_bag/all_tickets/:bagid",
                        component: ClientTicketsInsideBagAll,
                    },

                    {
                        path: "/client/branch/:unitid/:eventid/event_bags/inside_bag/sold_tickets/:bagid",
                        component: ClientTicketsInsideBagSold,
                    },
                    {
                        path: "/client/branch/:unitid/:eventid/event_bags/inside_bag/unsold_tickets/:bagid",
                        component: ClientTicketsInsideBagUnsold,
                    },
                ],
            },
            {
                path: "/client/branch/:unitid/:eventid/event_bags/inside_bag/all_tickets/offline",
                component: ClientTicketsInsideBagAllOffline,
            },
            {
                path: "/client/branch/:unitid/:eventid/event_bags/inside_bag/all_tickets/offline2",
                component: ClientTicketsInsideBagAllOffline2,
            },
            {
                path: "/client/branch/:unitid/:eventid/event_sellers",
                component: ClientEventSellers,
            },
            {
                path: "/client/branch/:unitid/:eventid/returned_bags",
                component: ClientReturnedBagsPage,
                children: [
                    {
                        path: "/client/branch/:unitid/:eventid/returned_bags",
                        component: ClientReturnedBagsTable,
                    },
                    {
                        path: "/client/branch/:unitid/:eventid/returned_bags/inside_bag/all_tickets",
                        component: ClientReturnedAllTickets,
                    },
                ],
            },
            {
                path: "/client/branch/:unitid/:eventid/daily_breakdown",
                component: ClientDailySellerBreakdown,
            },
            {
                path: "/client/branch/:unitid/:eventid/event_history",
                component: ClientEventHistory,
            },
        ],
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    strict: true,
});
export default router;
